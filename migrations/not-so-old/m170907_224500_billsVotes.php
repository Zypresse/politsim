<?php

use yii\db\Migration;

class m170907_224500_billsVotes extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('billsVotes', [
            'billId' => $this->integer()->unsigned()->notNull(),
            'postId' => $this->integer()->unsigned()->notNull(),
            'variant' => $this->integer(1)->unsigned()->notNull(),
        ]);
        $this->createIndex('bills2posts', 'billsVotes', ['billId', 'postId'], true);
        $this->addForeignKey('billsVotesBillRef', 'billsVotes', ['billId'], 'bills', ['id']);
        $this->addForeignKey('billsVotesPostRef', 'billsVotes', ['postId'], 'agenciesPosts', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('billsVotesBillRef', 'billsVotes');
        $this->dropForeignKey('billsVotesPostRef', 'billsVotes');
        $this->dropIndex('bills2posts', 'billsVotes');
        $this->dropTable('billsVotes');
    }

}
