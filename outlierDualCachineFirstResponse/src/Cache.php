<?php

// Define cache strategies
const CACHE_STRATEGY_SHORT_TERM = 'short-term';
const CACHE_STRATEGY_LONG_TERM = 'long-term';

// Define cache expiration times
const CACHE_EXPIRATION_SHORT_TERM = 300; // 5 minutes
const CACHE_EXPIRATION_LONG_TERM = 3600; // 1 hour

// Define error threshold and retry settings
const ERROR_THRESHOLD = 5;
const MAX_RETRIES = 3;
const RETRY_DELAY = 500; // milliseconds

// Define cache performance threshold
const CACHE_HIT_RATE_THRESHOLD = 0.8;

// Logger class to log errors and metrics
class Logger {
    private $logFile;

    public function __construct($logFile) {
        $this->logFile = $logFile;
    }

    public function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "$timestamp $message\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}

// Cache class to handle caching functionality
class Cache {
    private $cacheDir;
    private $logger;

    public function __construct($cacheDir, $logger) {
        $this->cacheDir = $cacheDir;
        $this->logger = $logger;
    }

    public function get($key, $strategy = CACHE_STRATEGY_SHORT_TERM) {
        $cacheFile = $this->getCacheFile($key, $strategy);
        if (!file_exists($cacheFile)) {
            return null;
        }
        $data = unserialize(file_get_contents($cacheFile));
        if ($data['expiration'] < time()) {
            // Cache expired, delete file and return null
            unlink($cacheFile);
            return null;
        }
        return $data['value'];
    }

    public function set($key, $value, $strategy = CACHE_STRATEGY_SHORT_TERM) {
        $cacheFile = $this->getCacheFile($key, $strategy);
        $expiration = time() + $this->getExpiration($strategy);
        $data = serialize(['value' => $value, 'expiration' => $expiration]);
        $this->writeWithRetry($cacheFile, $data);
    }

    private function getCacheFile($key, $strategy) {
        return $this->cacheDir . '/' . $strategy . '/' . $key . '.cache';
    }

    private function getExpiration($strategy) {
        return $strategy === CACHE_STRATEGY_SHORT_TERM
            ? CACHE_EXPIRATION_SHORT_TERM
            : CACHE_EXPIRATION_LONG_TERM;
    }

    private function writeWithRetry($file, $data) {
        $retryCount = 0;
        while ($retryCount < MAX_RETRIES) {
            try {
                file_put_contents($file, $data);
                break;
            } catch (Exception $e) {
                $this->logger->log("Error writing to cache file: " . $e->getMessage());
                $retryCount++;
                usleep(RETRY_DELAY * 1000);
            }
        }
        if ($retryCount >= MAX_RETRIES) {
            $this->logger->log("Failed to write to cache file after " . MAX_RETRIES . " retries");
        }
    }
}

// CacheMetrics class to track and log cache performance metrics
class CacheMetrics {
    private $logger;
    private $cacheHits = 0;
    private $cacheMisses = 0;

    public function __construct($logger) {
        $this->logger = $logger;
    }

    public function trackHit() {
        $this->cacheHits++;
    }

    public function trackMiss() {
        $this->cacheMisses++;
    }

    public function getHitRate() {
        $totalRequests = $this->cacheHits + $this->cacheMisses;
        return $totalRequests === 0 ? 0 : $this->cacheHits / $totalRequests;
    }

    public function logMetrics() {
        $metrics = [
            'cache_hits' => $this->cacheHits,
            'cache_misses' => $this->cacheMisses,
            'hit_rate' => $this->getHitRate(),
        ];
        $this->logger->log(json_encode($metrics));
    }
}

// Set up logging and caching
$logger = new Logger('cache.log');
$cache = new Cache('cache', $logger);
$cacheMetrics = new CacheMetrics($logger);

// Example usage
$key = 'example_key';
$value = 'example_value';

$cache->set($key, $value, CACHE_STRATEGY_SHORT_TERM);
$cacheMetrics->trackHit();

$retrievedValue = $cache->get($key, CACHE_STRATEGY_SHORT_TERM);
if ($retrievedValue !== null) {
    $cacheMetrics->trackHit();
} else {
    $cacheMetrics->trackMiss();
}

$cacheMetrics->logMetrics();

// Unit tests
class CacheTest extends PHPUnit\Framework\TestCase {
    private $cache;
    private $logger;

    public function setUp(): void {
        $this->logger = new Logger('test.log');
        $this->cache = new Cache('test_cache', $this->logger);
    }

    public function testGetAndSet() {
        $key = 'test_key';
        $value = 'test_value';
        $this->cache->set($key, $value);
        $retrievedValue = $this->cache->get($key);
        $this->assertEquals($value, $retrievedValue);
    }

    public function testCacheExpiration() {
        $key = 'test_key';
        $value = 'test_value';
        $this->cache->set($key, $value);
        sleep(CACHE_EXPIRATION_SHORT_TERM + 1);
        $retrievedValue = $this->cache->get($key);
        $this->assertNull($retrievedValue);
    }

    public function testErrorHandling() {
        $key = 'test_key';
        $value = 'test_value';
        chmod('test_cache', 0444); // Make cache directory read-only to simulate error
        try {
            $this->cache->set($key, $value);
        } catch (Exception $e) {
            $this->logger->log("Error writing to cache file: " . $e->getMessage());
        }
        chmod('test_cache', 0755); // Restore permissions
        $this->assertEquals(MAX_RETRIES, $this->logger->getRetryCount());
    }
}
