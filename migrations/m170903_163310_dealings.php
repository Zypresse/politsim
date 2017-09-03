<?php

use yii\db\Migration;

class m170903_163310_dealings extends Migration
{
 /*
  * `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	`type` UNSIGNED INTEGER(3) NOT NULL,
	`initiator` UNSIGNED INTEGER REFERENCES utr(id) NOT NULL,
	`receiver` UNSIGNED INTEGER REFERENCES utr(id) NOT NULL,
	`dateCreated` UNSIGNED INTEGER NOT NULL,
	`dateApproved` UNSIGNED INTEGER DEFAULT NULL
  */   
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
        $this->dropTable('dealings');
    }

}
