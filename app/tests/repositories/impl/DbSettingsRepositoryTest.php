<?php

namespace app\tests\repository\impl;

use app\repositories\impl\DbSettingsRepository;
use app\tests\AppTestCase;
use yii\db\Transaction;

class DbSettingsRepositoryTest extends AppTestCase
{
    /**
     * @var Transaction
     */
    private $transaction;

    public function testFetchSetting()
    {
        $repository = new DbSettingsRepository(\Yii::$app->db);

        $settingJson = $repository->fetchSetting('test-case');

        static::assertSame([
            'foo' => [
                'bar' => 'baz'
            ]
        ], $settingJson);
    }

    public function testHasContain()
    {
        $repository = new DbSettingsRepository(\Yii::$app->db);

        $contain = $repository->hasContain('test-case');

        static::assertTrue($contain);
    }

    /**
     * @throws \yii\db\Exception
     */
    public function testInsert()
    {
        $repository = new DbSettingsRepository(\Yii::$app->db);

        $repository->insert('new-value', 'hello world');

        static::assertTrue($repository->hasContain('new-value'));
        static::assertSame('hello world', $repository->fetchSetting('new-value'));
    }

    /**
     * @throws \yii\db\Exception
     */
    public function testUpdate()
    {
        $repository = new DbSettingsRepository(\Yii::$app->db);

        $repository->update('test-case', 'hello world');

        static::assertTrue($repository->hasContain('test-case'));
        static::assertSame('hello world', $repository->fetchSetting('test-case'));
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function setUp()
    {
        parent::setUp();
        $connection = \Yii::$app->db;

        $connection->createCommand()->truncateTable(DbSettingsRepository::TABLE)->execute();
        $connection->createCommand()->insert(DbSettingsRepository::TABLE, [
            'key' => 'test-case',
            'value' => [
                'foo' => [
                    'bar' => 'baz'
                ]
            ]
        ])->execute();

        $this->transaction = $connection->beginTransaction();
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function tearDown()
    {
        $this->transaction->rollBack();
        parent::tearDown();
    }
}
