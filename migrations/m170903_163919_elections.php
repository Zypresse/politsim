<?php

use yii\db\Migration;

class m170903_163919_elections extends Migration
{
    
    public function safeUp()
    {
        /*
         * `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	`whomType` UNSIGNED INTEGER(1) NOT NULL,
	`whomId` UNSIGNED INTEGER DEFAULT NULL,
	`whoType` UNSIGNED INTEGER(1) NOT NULL,
	`whoId` UNSIGNED INTEGER DEFAULT NULL,
	`settings` UNSIGNED INTEGER(4) NOT NULL,
	`initiatorElectionId` UNSIGNED INTEGER REFERENCES elections(id) DEFAULT NULL,
	`dateRegistrationStart` UNSIGNED INTEGER NOT NULL,
	`dateRegistrationEnd` UNSIGNED INTEGER NOT NULL,
	`dateVotingStart` UNSIGNED INTEGER NOT NULL,
	`dateVotingEnd` UNSIGNED INTEGER NOT NULL,
	`results` TEXT DEFAULT NULL
         */
        $this->createTable('elections', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'whomType' => $this->integer(2)->unsigned()->notNull(),
            'whomId' => $this->integer()->unsigned()->null(),
            'whoType' => $this->integer(2)->unsigned()->notNull(),
            'whoId' => $this->integer()->unsigned()->null(),
            'settings' => $this->integer(4)->unsigned()->notNull(),
            'parentElectionsId' => $this->integer()->unsigned()->null(),
            'dateRegistrationStart' => $this->integer()->unsigned()->notNull(),
            'dateRegistrationEnd' => $this->integer()->unsigned()->notNull(),
            'dateVotingStart' => $this->integer()->unsigned()->notNull(),
            'dateVotingEnd' => $this->integer()->unsigned()->notNull(),
            'dateResultsPublished' => $this->integer()->unsigned()->null(),
            'results' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('whomElections', 'elections', ['whomType', 'whomId']);
        $this->createIndex('whoElections', 'elections', ['whoType', 'whoId']);
        $this->createIndex('parentElectionsId', 'elections', ['parentElectionsId']);
        $this->createIndex('dateRegistrationStart', 'elections', ['dateRegistrationStart']);
        $this->createIndex('dateRegistrationEnd', 'elections', ['dateRegistrationEnd']);
        $this->createIndex('dateVotingStart', 'elections', ['dateVotingStart']);
        $this->createIndex('dateVotingEnd', 'elections', ['dateVotingEnd']);
        $this->createIndex('dateResultsPublished', 'elections', ['dateResultsPublished']);
    }

    public function safeDown()
    {
        $this->dropTable('elections');
    }

}
