<?php

use yii\db\Migration;

/**
 * Handles the creation of table `accountProviders`.
 */
class m180313_185814_create_accountProviders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('accountProviders', [
            'accountId' => $this->integer()->notNull(),
            'sourceId' => $this->integer()->notNull(),
            'sourceType' => $this->smallInteger(2)->notNull(),
        ]);
        $this->createIndex('accountProvidersPrimary', 'accountProviders', ['sourceId', 'sourceType'], true);
        $this->addForeignKey('accountProvidersToAccount', 'accountProviders', 'accountId', 'accounts', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('accountProvidersToAccount', 'accountProviders');
        $this->dropIndex('accountProvidersPrimary', 'accountProviders');
        $this->dropTable('accountProviders');
    }
}
