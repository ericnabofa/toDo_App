<?php
require_once __DIR__ . '/src/Cache.php';
require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/src/CacheMetrics.php';

// Initialize logger, cache, and metrics tracker
$logger = new Logger(__DIR__ . '/logs/cache.log');
$cache = new Cache(__DIR__ . '/cache', $logger);
$cacheMetrics = new CacheMetrics($logger);

// Example usage of caching
$key = 'sample_key';
$value = 'sample_value';

// Set cache value
$cache->set($key, $value, CACHE_STRATEGY_SHORT_TERM);

// Attempt to retrieve cached value
$retrievedValue = $cache->get($key, CACHE_STRATEGY_SHORT_TERM);
if ($retrievedValue !== null) {
    echo "Cache Hit: " . $retrievedValue . PHP_EOL;
    $cacheMetrics->trackHit();
} else {
    echo "Cache Miss" . PHP_EOL;
    $cacheMetrics->trackMiss();
}

// Log metrics
$cacheMetrics->logMetrics();
