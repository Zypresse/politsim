<?php

use yii\db\Migration;

class m170907_233200_dealings extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('dealings', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'type' => $this->integer(3)->unsigned()->notNull(),
            'initiator' => $this->integer()->unsigned()->notNull(),
            'receiver' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateApproved' => $this->integer()->unsigned()->null(),
            'amount' => $this->double()->notNull(),
            'items' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('typeDealings', 'dealings', ['type']);
        $this->createIndex('initiatorDealings', 'dealings', ['initiator']);
        $this->createIndex('receiverDealings', 'dealings', ['receiver']);
        $this->createIndex('dateCreatedDealings', 'dealings', ['dateCreated']);
        $this->createIndex('dateApprovedDealings', 'dealings', ['dateApproved']);
    }

    public function safeDown()
    {
        $this->dropIndex('typeDealings', 'dealings');
        $this->dropIndex('initiatorDealings', 'dealings');
        $this->dropIndex('receiverDealings', 'dealings');
        $this->dropIndex('dateCreatedDealings', 'dealings');
        $this->dropIndex('dateApprovedDealings', 'dealings');
        $this->dropTable('dealings');
    }

}
