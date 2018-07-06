<?php


namespace app\repositories\impl;


use app\repositories\SettingsRepository;
use yii\db\Connection;
use yii\db\Query;

/**
 * Class DbSettingsRepository implementing {@link SettingRepository} with storage in Database/
 * @package app\repositories\impl
 * @author Daniil (razornd) Razorenov <razor@razornd.ru>
 */
class DbSettingsRepository implements SettingsRepository
{

    /** @var string Column name in database with setting value */
    const VALUE = 'value';
    /** @var string Table in database where setting store */
    const TABLE = '{{%setting}}';
    /** @var string Column name in database with setting key */
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
    public function fetchSetting(string $key)
    {
        $value = (new Query())
            ->select(static::VALUE)
            ->from(static::TABLE)
            ->where([static::KEY => $key])
            ->limit(1)
            ->scalar($this->db);
        return is_string($value) ? json_decode($value, true) : null;
    }

    /**
     * @inheritdoc
     * @throws \yii\db\Exception
     */
    public function saveSetting(string $key, $value): void
    {
        if ($this->hasContain($key)) {
            $this->update($key, $value);
        } else {
            $this->insert($key, $value);
        }
    }

    /**
     * @inheritdoc
     * @throws \yii\db\Exception
     */
    public function insert(string $key, $value): void
    {
        $this->db
            ->createCommand()
            ->insert(static::TABLE, [
                static::KEY => $key,
                static::VALUE => $value
            ])->execute();
    }

    /**
     * @inheritdoc
     * @throws \yii\db\Exception
     */
    public function update(string $key, $value): void
    {
        $this->db
            ->createCommand()
            ->update(static::TABLE, [
                static::VALUE => $value
            ], [
                static::KEY => $key
            ])->execute();
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
