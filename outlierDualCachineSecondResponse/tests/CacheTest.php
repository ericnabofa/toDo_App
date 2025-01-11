<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Cache.php';
require_once __DIR__ . '/../src/ErrorHandler.php';
require_once __DIR__ . '/../src/CacheMonitor.php';

class CacheTest extends TestCase {
    private $cache;

    protected function setUp(): void {
        $this->cache = new Cache('cache/');
    }

    public function testGetAndSet() {
        $key = 'testKey';
        $value = 'testValue';
        $this->cache->set($key, $value);
        $this->assertEquals($value, $this->cache->get($key));
    }

    public function testCacheExpiration() {
        $key = 'testKey';
        $value = 'testValue';
        $this->cache->set($key, $value);
        sleep(6); // Wait to simulate cache expiration
        $this->assertNull($this->cache->get($key));
    }

    public function testErrorHandling() {
        $errorHandler = new ErrorHandler('logs/error.log');
        $exception = new Exception('Test exception');
        $errorHandler->handleError($exception);
        $this->assertFileExists('logs/error.log');
    }
}
