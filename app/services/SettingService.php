<?php

namespace app\services;

use app\repositories\SettingsRepository;
use yii\caching\CacheInterface;

/**
 * Class SettingService store/cache project settings
 * @package app\services
 * @author Daniil (razornd) Razorenov <razor@razornd.ru>
 */
class SettingService
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var array local cache
     */
    private $data = [];

    /**
     * SettingService constructor.
     * @param SettingsRepository $repository
     * @param CacheInterface $cache
     */
    public function __construct(SettingsRepository $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Get setting with passed key
     * @param string $key setting key
     * @return mixed setting value
     */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = $this->cache->getOrSet(static::cacheKey($key), function () use ($key) {
                return $this->fetchSettings($key);
            });
        }
        return $this->data[$key];
    }

    /**
     * Set setting with passed key and value
     * @param string $key setting key
     * @param mixed $value setting value
     */
    public function set(string $key, $value): void
    {
        $this->repository->saveSetting($key, $value);
        $this->cache->set($key, $value);
        $this->data[$key] = $value;
    }

    /**
     * Fetch setting value direct from repository
     * @param string $key setting key
     * @return mixed|null setting value
     */
    public function fetchSettings(string $key)
    {
        return $this->repository->fetchSetting($key);
    }

    /**
     * Create cache key with namespace from setting key
     * @param string $key setting key
     * @return string cache key
     */
    static function cacheKey(string $key): string
    {
        return "SettingService:$key";
    }
}
