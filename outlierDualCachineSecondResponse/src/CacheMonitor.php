<?php

class CacheMonitor {
    private $cache;
    private $metricsLog;

    public function __construct($cache, $metricsLog) {
        $this->cache = $cache;
        $this->metricsLog = $metricsLog;
    }

    public function trackCachePerformance() {
        $hitCount = 0;
        $missCount = 0;
        $accessTimes = [];
        foreach ($this->cache->getCacheFiles() as $cacheFile) {
            $data = unserialize(file_get_contents($cacheFile));
            if ($data['expiration'] > time()) {
                $hitCount++;
            } else {
                $missCount++;
            }
            $accessTimes[] = $data['accessTime'];
        }
        $metricsData = [
            'hitRate' => $hitCount / ($hitCount + $missCount),
            'averageAccessTime' => array_sum($accessTimes) / count($accessTimes)
        ];
        file_put_contents($this->metricsLog, json_encode($metricsData) . PHP_EOL, FILE_APPEND);
    }
}
