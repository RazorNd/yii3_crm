<?php


namespace app\repositories;

/**
 * Interface SettingsRepository repository for store settings
 * @package app\repositories
 * @author Daniil (razornd) Razorenov <razor@razornd.ru>
 */
interface SettingsRepository
{
    /**
     * fetch setting value from storage
     *
     * @param string $key setting key
     * @return null|mixed setting or null if setting with passed key hasn't exist
     */
    public function fetchSetting(string $key);

    /**
     * save setting in storage
     *
     * @param string $key setting key
     * @param mixed $value setting value
     */
    public function saveSetting(string $key, $value): void;

    /**
     * check existing setting with passed key in storage
     *
     * @param string $key setting key
     * @return bool {@code true} if exist or {@code false}
     */
    public function hasContain(string $key): bool;
}
