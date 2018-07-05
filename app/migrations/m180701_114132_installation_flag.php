<?php

use yii\db\Migration;

/**
 * Class m180701_114132_installation_flag
 */
class m180701_114132_installation_flag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\repositories\impl\DbSettingsRepository::TABLE, [
            'key' => \app\helpers\InstallationHelper::SETTING_APP_INSTALLED,
            'value' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\repositories\impl\DbSettingsRepository::TABLE, [
            \app\repositories\impl\DbSettingsRepository::KEY => \app\helpers\InstallationHelper::SETTING_APP_INSTALLED
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180701_114132_installation_flag cannot be reverted.\n";

        return false;
    }
    */
}
