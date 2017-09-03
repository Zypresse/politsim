<?php

use yii\db\Migration;

class m170903_164826_electionsRequests extends Migration
{
    
    public function safeUp()
    {
        /*
         * `electionId` UNSIGNED INTEGER REFERENCES elections(id) NOT NULL,
	`type` UNSIGNED INTEGER(1) NOT NULL,
	`objectId` UNSIGNED INTEGER NOT NULL,
	`variant` UNSIGNED INTEGER(3) NOT NULL,
	`dateCreated` UNSIGNED INTEGER NOT NULL
         */
        $this->createTable('electionsRequests', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'electionId' => $this->integer()->unsigned()->notNull(),
            'type' => $this->integer(2)->unsigned()->notNull(),
            'objectId' => $this->integer()->unsigned()->notNull(),
            'variant' => $this->integer(3)->unsigned()->null(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateApproved' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('electionIdElectionsRequests', 'electionsRequests', ['electionId']);
        $this->createIndex('typeElectionsRequests', 'electionsRequests', ['type']);
        $this->createIndex('objectIdElectionsRequests', 'electionsRequests', ['objectId']);
        $this->createIndex('variantElectionsRequests', 'electionsRequests', ['electionId', 'variant'], true);
        $this->createIndex('dateCreatedElectionsRequests', 'electionsRequests', ['dateCreated']);
        $this->createIndex('dateApprovedElectionsRequests', 'electionsRequests', ['dateApproved']);
    }

    public function safeDown()
    {
        $this->dropTable('electionsRequests');
    }

}
