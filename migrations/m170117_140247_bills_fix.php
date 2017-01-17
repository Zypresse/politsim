<?php

use yii\db\Migration;

class m170117_140247_bills_fix extends Migration
{
    public function safeUp()
    {
        $this->dropTable('bills');
        
        $this->createTable('bills', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            
            // двойная привязка к юзеру и посту для того чтобы корректно отображалось после того как юзер перейдёт на другой пост
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES agenciesPosts(id) DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingFinished' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateFinished' => 'UNSIGNED INTEGER DEFAULT NULL',
            'isApproved' => 'BOOLEAN NOT NULL DEFAULT 0',

            'vetoPostId' => 'UNSIGNED INTEGER REFERENCES agenciesPosts(id) DEFAULT NULL',
            'isDictatorBill' => 'BOOLEAN NOT NULL DEFAULT 0',

            'votesPlus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',

            'data' => 'TEXT DEFAULT NULL'
        ]);
        $this->createIndex('billsState', 'bills', ['stateId']);
        $this->createIndex('billsStateByStatus', 'bills', ['stateId', 'isApproved']);
        
    }

    public function safeDown()
    {
        
    }
}
