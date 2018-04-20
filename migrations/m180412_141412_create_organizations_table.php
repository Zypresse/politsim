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
            'ideologyId' => $this->integer(3)->unsigned()->notNull(),
            'leaderPostId' => $this->integer()->unsigned()->null(),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'fame' => $this->integer()->notNull()->defaultValue(0),
            'trust' => $this->integer()->notNull()->defaultValue(0),
            'success' => $this->integer()->notNull()->defaultValue(0),
            'text' => $this->text()->null(),
            'textHtml' => $this->text()->null(),
            'membersCount' => $this->integer(5)->unsigned()->notNull()->defaultValue(1),
            'joiningRules' => $this->integer(2)->unsigned()->notNull(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('nameOrganizations', $this->table, ['name']);
        $this->createIndex('nameShortOrganizations', $this->table, ['nameShort']);
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
        
        $this->createTable('organizationsPosts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'orgId' => $this->integer()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'powers' => $this->integer(4)->unsigned()->notNull(),
            'appointmentType' => $this->integer(2)->unsigned()->notNull(),
            'successorId' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('nameOrganizationsPosts', 'organizationsPosts', ['name']);
        $this->createIndex('nameShortOrganizationsPosts', 'organizationsPosts', ['nameShort']);
        $this->addForeignKey('orgIdForeignOrganizationsPosts', 'organizationsPosts', ['orgId'], $this->table, ['id']);
        $this->addForeignKey('userIdForeignOrganizationsPosts', 'organizationsPosts', ['userId'], 'users', ['id']);
        $this->addForeignKey('successorIdForeignOrganizationsPosts', 'organizationsPosts', ['successorId'], 'users', ['id']);
        $this->addForeignKey('leaderPostIdForeignOrganizations', $this->table, ['leaderPostId'], 'organizationsPosts', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropForeignKey('leaderPostIdForeignOrganizations', $this->table);
        $this->dropIndex('nameOrganizationsPosts', 'organizationsPosts');
        $this->dropIndex('nameShortOrganizationsPosts', 'organizationsPosts');
        $this->dropForeignKey('orgIdForeignOrganizationsPosts', 'organizationsPosts');
        $this->dropForeignKey('userIdForeignOrganizationsPosts', 'organizationsPosts');
        $this->dropForeignKey('successorIdForeignOrganizationsPosts', 'organizationsPosts');
        $this->dropTable('organizationsPosts');
        
        $this->dropIndex('user2org', 'organizationsMemberships');
        $this->dropIndex('dateCreatedOrganizationsMemberships', 'organizationsMemberships');
        $this->dropIndex('dateApprovedOrganizationsMemberships', 'organizationsMemberships');
        $this->dropForeignKey('user2orgOrgForeignOrganizations', 'organizationsMemberships');
        $this->dropForeignKey('user2orgUserForeignOrganizations', 'organizationsMemberships');
        $this->dropTable('organizationsMemberships');
        
        $this->dropIndex('nameOrganizations', $this->table);
        $this->dropIndex('nameShortOrganizations', $this->table);
        $this->dropIndex('dateCreatedOrganizations', $this->table);
        $this->dropIndex('dateDeletedOrganizations', $this->table);
        $this->dropIndex('utrOrganizations', $this->table);
        $this->dropTable($this->table);
    }
}
