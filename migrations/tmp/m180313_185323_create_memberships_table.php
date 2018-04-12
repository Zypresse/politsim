<?php

use yii\db\Migration;

class m180313_185323_create_memberships_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('memberships', [
            'userId' => $this->integer()->unsigned()->notNull(),
            'partyId' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateApproved' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('user2party', 'memberships', ['userId', 'partyId'], true);
        $this->createIndex('dateCreatedMemberships', 'memberships', ['dateCreated']);
        $this->createIndex('dateApprovedMemberships', 'memberships', ['dateApproved']);
        $this->addForeignKey('userIdMemberships', 'memberships', ['userId'], 'users', ['id']);
        $this->addForeignKey('partyIdMemberships', 'memberships', ['partyId'], 'parties', ['id']);
    }

    public function safeDown()
    {
        $this->dropIndex('user2party', 'memberships');
        $this->dropIndex('dateCreatedMemberships', 'memberships');
        $this->dropIndex('dateApprovedMemberships', 'memberships');
        $this->dropForeignKey('userIdMemberships', 'memberships');
        $this->dropForeignKey('partyIdMemberships', 'memberships');
        $this->dropTable('memberships');
    }

}
