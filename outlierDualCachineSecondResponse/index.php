<?php
require_once __DIR__ . '/src/Cache.php';
require_once __DIR__ . '/src/ErrorHandler.php';
require_once __DIR__ . '/src/CacheMonitor.php';

$cache = new Cache(__DIR__ . '/cache/');
$cache->set('test_key', 'test_value');
echo $cache->get('test_key');
