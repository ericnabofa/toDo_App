<?php

class Cache {
    const SHORT_TERM_EXPIRATION = 300; // 5 minutes
    const LONG_TERM_EXPIRATION = 3600; // 1 hour

    private $cacheDir;
    private $strategy;

    public function __construct($cacheDir, $strategy = 'short-term') {
        $this->cacheDir = $cacheDir;
        $this->strategy = $strategy;
    }

    public function get($key) {
        $cacheFile = $this->getCacheFile($key);
        if (file_exists($cacheFile)) {
            $data = unserialize(file_get_contents($cacheFile));
            if ($data['expiration'] > time()) {
                return $data['value'];
            } else {
                unlink($cacheFile); // Cache expired, delete file
            }
        }
        return null;
    }

    public function set($key, $value) {
        $cacheFile = $this->getCacheFile($key);
        $expiration = time() + ($this->strategy === 'short-term' ? self::SHORT_TERM_EXPIRATION : self::LONG_TERM_EXPIRATION);
        $data = ['value' => $value, 'expiration' => $expiration];
        file_put_contents($cacheFile, serialize($data));
    }

    private function getCacheFile($key) {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}
