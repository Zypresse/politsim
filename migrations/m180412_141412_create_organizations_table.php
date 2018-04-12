<?php

use yii\db\Migration;

/**
 * Handles the creation of table `organizations`.
 */
class m180412_141412_create_organizations_table extends Migration
{
    
    private $table = 'organizations';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'flag' => $this->string(255)->null(),
            'anthem' => $this->string(255)->null(),
            'leaderId' => $this->integer()->unsigned()->null(),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('nameOrganizations', $this->table, ['name']);
        $this->createIndex('nameShortOrganizations', $this->table, ['nameShort']);
        $this->addForeignKey('leaderIdForeignOrganizations', $this->table, ['leaderId'], 'users', ['id']);
        $this->createIndex('dateCreatedOrganizations', $this->table, ['dateCreated']);
        $this->createIndex('dateDeletedOrganizations', $this->table, ['dateDeleted']);
        $this->createIndex('utrOrganizations', $this->table, ['utr'], true);
        
        $this->createTable('organizationsMemberships', [
            'userId' => $this->integer()->unsigned()->notNull(),
            'orgId' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateApproved' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('user2org', 'organizationsMemberships', ['userId', 'orgId'], true);
        $this->createIndex('dateCreatedOrganizationsMemberships', 'organizationsMemberships', ['dateCreated']);
        $this->createIndex('dateApprovedOrganizationsMemberships', 'organizationsMemberships', ['dateApproved']);
        $this->addForeignKey('user2orgOrgForeignOrganizations', 'organizationsMemberships', ['orgId'], $this->table, ['id']);
        $this->addForeignKey('user2orgUserForeignOrganizations', 'organizationsMemberships', ['userId'], 'users', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('user2org', 'organizationsMemberships');
        $this->dropIndex('dateCreatedOrganizationsMemberships', 'organizationsMemberships');
        $this->dropIndex('dateApprovedOrganizationsMemberships', 'organizationsMemberships');
        $this->dropForeignKey('user2orgOrgForeignOrganizations', 'organizationsMemberships');
        $this->dropForeignKey('user2orgUserForeignOrganizations', 'organizationsMemberships');
        $this->dropTable('organizationsMemberships');
        
        $this->dropIndex('nameOrganizations', $this->table);
        $this->dropIndex('nameShortOrganizations', $this->table);
        $this->dropForeignKey('leaderIdForeignOrganizations', $this->table);
        $this->dropIndex('dateCreatedOrganizations', $this->table);
        $this->dropIndex('dateDeletedOrganizations', $this->table);
        $this->dropIndex('utrOrganizations', $this->table);
        $this->dropTable($this->table);
    }
}
