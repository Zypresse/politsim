<?php

use yii\db\Migration;

class m170113_094614_add_bills_field_accepted extends Migration
{
    
    public function safeUp()
    {
        $this->renameTable('bills', 'tmpBills');
        
        $this->createTable('bills', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            
            // двойная привязка к юзеру и посту для того чтобы корректно отображалось после того как юзер перейдёт на другой пост
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingFinished' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateFinished' => 'UNSIGNED INTEGER DEFAULT NULL',
            'isApproved' => 'BOOLEAN NOT NULL DEFAULT 0',

            'vetoPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            'isDictatorBill' => 'BOOLEAN NOT NULL DEFAULT 0',

            'votesPlus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',

            'data' => 'TEXT DEFAULT NULL'
        ]);
        $this->createIndex('billsState', 'bills', ['stateId']);
        $this->createIndex('billsStateByStatus', 'bills', ['stateId', 'isApproved']);
        
        $this->execute("
            INSERT INTO bills 
            (id,protoId,stateId,userId,postId,dateCreated,dateFinished,vetoPostId,isDictatorBill,votesPlus,votesMinus,votesAbstain,data)
            SELECT id,protoId,stateId,userId,postId,dateCreated,dateApproved,vetoPostId,isDictatorBill,votesPlus,votesMinus,votesAbstain,data FROM `tmpBills`
        ");
        $this->dropTable('tmpBills');
    }

    public function safeDown()
    {
        $this->renameTable('bills', 'tmpBills');
        
        $this->createTable('bills', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            
            // двойная привязка к юзеру и посту для того чтобы корректно отображалось после того как юзер перейдёт на другой пост
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL',

            'vetoPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            'isDictatorBill' => 'BOOLEAN NOT NULL DEFAULT 0',

            'votesPlus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',

            'data' => 'TEXT DEFAULT NULL'
        ]);
        
        $this->execute("
            INSERT INTO bills 
            (id,protoId,stateId,userId,postId,dateCreated,dateApproved,vetoPostId,isDictatorBill,votesPlus,votesMinus,votesAbstain,data)
            SELECT id,protoId,stateId,userId,postId,dateCreated,dateFinished,vetoPostId,isDictatorBill,votesPlus,votesMinus,votesAbstain,data FROM `tmpBills`
        ");
        $this->dropTable('tmpBills');
    }
    
}
