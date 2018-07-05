<?php


namespace app\repositories\impl;


use app\repositories\SettingsRepository;
use yii\db\Connection;
use yii\db\Query;

/**
 * Class DbSettingsRepository
 * @package app\repositories\impl
 * @author Daniil (razornd) Razorenov <razor@razornd.ru>
 */
class DbSettingsRepository implements SettingsRepository
{

    const VALUE = 'value';
    const TABLE = '{{%setting}}';
    const KEY = 'key';

    /**
     * @var Connection database connection
     */
    private $db;

    /**
     * DbSettingsRepository constructor.
     * @param Connection $db database connection
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritdoc
     */
    public function fetchSettingJson(string $key):?string
    {
        return (new Query())
            ->select(static::VALUE)
            ->from(static::TABLE)
            ->where([static::KEY => $key])
            ->limit(1)
            ->scalar($this->db);
    }

    /**
     * @inheritdoc
     */
    public function saveSettingJson(string $key, string $value): void
    {
        if ($this->hasContain($key)) {
            $this->update($key, $value);
        } else {
            $this->insert($key, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function insert(string $key, $value): void
    {
        $this->db
            ->createCommand()
            ->insert(static::TABLE, [
                static::KEY => $key,
                static::VALUE => $value
            ]);
    }

    /**
     * @inheritdoc
     */
    public function update(string $key, $value): void
    {
        $this->db
            ->createCommand()
            ->update(static::TABLE, [
                static::VALUE => $value
            ], [
                static::KEY => $key
            ]);
    }

    /**
     * @inheritdoc
     */
    public function hasContain(string $key): bool
    {
        $exists = (new Query())
            ->from(static::TABLE)
            ->where([static::KEY => $key])
            ->exists($this->db);
        return $exists;
    }
}