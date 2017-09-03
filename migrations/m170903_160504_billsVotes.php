<?php

use yii\db\Migration;

class m170903_160504_billsVotes extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('billsVotes', [
            'billId' => $this->integer()->unsigned()->notNull(),
            'postId' => $this->integer()->unsigned()->notNull(),
            'variant' => $this->integer(1)->unsigned()->notNull(),
        ]);
        $this->createIndex('bills2posts', 'billsVotes', ['billId', 'postId'], true);
    }

    public function safeDown()
    {
        $this->dropTable('billsVotes');
    }

}
