<?php

namespace app\services;

use app\repositories\SettingsRepository;
use yii\caching\CacheInterface;

/**
 * Class SettingService
 * @package app\services
 * @author Daniil razornd Razorenov <razor@razornd.ru>
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


    public function __construct(SettingsRepository $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    public function get(string $key)
    {
        return $this->cache->getOrSet(static::cacheKey($key), function () use ($key) {
            return $this->fetchSettings($key);
        });
    }

    public function set(string $key, $value) {
        $this->repository->saveSettingJson($key, json_encode($value));
    }

    public function fetchSettings(string $key)
    {
        $settingJson = $this->repository->fetchSettingJson($key);

        if (is_string($settingJson)) {
            return json_decode($settingJson);
        }
        return null;
    }

    static function cacheKey(string $key)
    {
        return "SettingService:$key";
    }
}