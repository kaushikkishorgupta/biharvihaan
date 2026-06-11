<?php

namespace App\Services;

class CacheService {
    private $cacheDir;

    public function __construct() {
        // Create cache directory in writable folder inside the workspace
        $this->cacheDir = dirname(__DIR__, 2) . '/writable/cache';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Retrieve cache item by key
     * @param string $key Unique cache identifier
     * @return mixed Unserialized cached content or null if expired/non-existent
     */
    public function get($key) {
        $file = $this->getCacheFilePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);
        if (!$data || time() > $data['expires_at']) {
            // Cache expired, remove file
            $this->delete($key);
            return null;
        }

        return unserialize($data['value']);
    }

    /**
     * Write cache item by key
     * @param string $key Unique cache identifier
     * @param mixed $value Value to store
     * @param int $ttl Lifetime in seconds (default: 300 seconds)
     */
    public function set($key, $value, $ttl = 300) {
        $file = $this->getCacheFilePath($key);
        $data = [
            'expires_at' => time() + $ttl,
            'value' => serialize($value)
        ];
        
        file_put_contents($file, json_encode($data));
    }

    /**
     * Delete cache item by key
     */
    public function delete($key) {
        $file = $this->getCacheFilePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Clear all cached files in directory
     */
    public function clear() {
        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Resolves cache key to a safe absolute file path
     */
    private function getCacheFilePath($key) {
        $safeKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
        return $this->cacheDir . '/' . md5($safeKey) . '.cache';
    }
}
