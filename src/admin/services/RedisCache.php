<?php
// services/RedisCache.php
require_once 'CacheInterface.php';

class RedisCache implements CacheInterface {
    private $redis;
    private $isConnected = false;

    public function __construct(string $host = '127.0.0.1', int $port = 6379, string $password) {
        try {
            $this->redis = new Redis();
            $this->redis->connect($host, $port);
            if ($password) {
                $this->redis->auth($password);
            }
            $this->isConnected = true;
        } catch (Exception $e) {
            error_log("Redis connection failed: " . $e->getMessage());
            $this->isConnected = false;
        }
    }

    public function get(string $key) {
        if (!$this->isConnected) return null;
        try {
            $value = $this->redis->get($key);
            return $value ? unserialize($value) : null;
        } catch (Exception $e) {
            error_log("Redis get error: " . $e->getMessage());
            return null;
        }
    }

    public function set(string $key, $value, int $ttl = 0): bool {
        if (!$this->isConnected) return false;
        try {
            $serialized = serialize($value);
            if ($ttl > 0) {
                return $this->redis->setex($key, $ttl, $serialized);
            } else {
                return $this->redis->set($key, $serialized);
            }
        } catch (Exception $e) {
            error_log("Redis set error: " . $e->getMessage());
            return false;
        }
    }

    public function delete(string $key): bool {
        if (!$this->isConnected) return false;
        try {
            return $this->redis->del($key) > 0;
        } catch (Exception $e) {
            error_log("Redis delete error: " . $e->getMessage());
            return false;
        }
    }

    public function clear(): bool {
        if (!$this->isConnected) return false;
        try {
            return $this->redis->flushDB();
        } catch (Exception $e) {
            error_log("Redis clear error: " . $e->getMessage());
            return false;
        }
    }

    public function has(string $key): bool {
        if (!$this->isConnected) return false;
        try {
            return $this->redis->exists($key);
        } catch (Exception $e) {
            error_log("Redis exists error: " . $e->getMessage());
            return false;
        }
    }
}