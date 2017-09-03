<?php

use yii\db\Migration;

class m170903_175858_memberships extends Migration
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
    }

    public function safeDown()
    {
        $this->dropTable('memberships');
    }

}
