<?php


namespace app\repositories;

/**
 * Interface SettingsRepository
 * @package app\repositories
 * @author Daniil razornd Razorenov <razor@razornd.ru>
 */
interface SettingsRepository
{
    /**
     * fetch JSON with setting from storage
     *
     * @param string $key setting key
     * @return null|string json with setting or null if setting with passed key hasn't exist
     */
    public function fetchSettingJson(string $key):?string;

    /**
     * save setting in storage
     *
     * @param string $key setting key
     * @param string $value setting value
     */
    public function saveSettingJson(string $key, string $value): void;

    /**
     * check existing setting with passed key in storage
     *
     * @param string $key setting key
     * @return bool {@code true} if exist or {@code false}
     */
    public function hasContain(string $key): bool;
}