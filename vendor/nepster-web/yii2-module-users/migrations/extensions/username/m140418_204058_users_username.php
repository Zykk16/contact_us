<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Добавление дополнительного поля username
 */
class m140418_204058_users_username extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'username', Schema::TYPE_STRING . ' NOT NULL AFTER `id`');
        $this->createIndex('{{%users_username}}', '{{%users}}', 'username', true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'username');
    }
}
