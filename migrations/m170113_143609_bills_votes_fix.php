<?php

use yii\db\Migration;

class m170113_143609_bills_votes_fix extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('billsVotes');
        $this->createTable('billsVotes', [
            'billId' => 'UNSIGNED INTEGER REFERENCES bills(id) NOT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES agenciesPosts(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        $this->createIndex('billVotePost', 'billsVotes', ['billId', 'postId'], true);
    }

    public function safeDown()
    {
        $this->dropTable('billsVotes');
        $this->createTable('billsVotes', [
            'billId' => 'UNSIGNED INTEGER REFERENCES bills(id) NOT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES agenciesPosts(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        $this->createIndex('billVotePost', 'billsVotes', ['billId', 'postId'], true);
    }
    
}
