<?php

use yii\db\Migration;

class m170903_155544_bills extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('bills', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'protoId' => $this->integer(4)->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'initiatorId' => $this->integer()->unsigned()->null(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateVotingFinished' => $this->integer()->unsigned()->null(),
            'dateFinished' => $this->integer()->unsigned()->null(),
            'isApproved' => $this->boolean()->notNull()->defaultValue(false),
            'vetoPostId' => $this->integer()->unsigned()->null(),
            'isDictatorBill' => $this->boolean()->notNull()->defaultValue(false),
            'votesPlus' => $this->integer(5)->unsigned()->notNull()->defaultValue(0),
            'votesMinus' => $this->integer(5)->unsigned()->notNull()->defaultValue(0),
            'votesAbstain' => $this->integer(5)->unsigned()->notNull()->defaultValue(0),
            'data' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('protoIdBills', 'bills', ['protoId']);
        $this->createIndex('stateIdBills', 'bills', ['stateId']);
        $this->createIndex('initiatorIdBills', 'bills', ['initiatorId']);
        $this->createIndex('dateCreatedBills', 'bills', ['dateCreated']);
        $this->createIndex('dateVotingFinishedBills', 'bills', ['dateVotingFinished']);
        $this->createIndex('dateFinishedBills', 'bills', ['dateFinished']);
        $this->createIndex('isApprovedBills', 'bills', ['isApproved']);
        $this->createIndex('vetoPostIdBills', 'bills', ['vetoPostId']);
        $this->createIndex('isDictatorBillBills', 'bills', ['isDictatorBill']);
    }

    public function safeDown()
    {
        $this->dropTable('bills');
    }

}
