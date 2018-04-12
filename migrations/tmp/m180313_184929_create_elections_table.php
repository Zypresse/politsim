<?php

use yii\db\Migration;

class m180313_184929_create_elections_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('elections', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'whomType' => $this->integer(2)->unsigned()->notNull(),
            'whomId' => $this->integer()->unsigned()->null(),
            'whoType' => $this->integer(2)->unsigned()->notNull(),
            'whoId' => $this->integer()->unsigned()->null(),
            'settings' => $this->integer(4)->unsigned()->notNull(),
            'parentId' => $this->integer()->unsigned()->null(),
            'dateRegistrationStart' => $this->integer()->unsigned()->notNull(),
            'dateRegistrationEnd' => $this->integer()->unsigned()->notNull(),
            'dateVotingStart' => $this->integer()->unsigned()->notNull(),
            'dateVotingEnd' => $this->integer()->unsigned()->notNull(),
            'dateResultsPublished' => $this->integer()->unsigned()->null(),
            'results' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('whomElections', 'elections', ['whomType', 'whomId']);
        $this->createIndex('whoElections', 'elections', ['whoType', 'whoId']);
        $this->createIndex('parentElectionsId', 'elections', ['parentId']);
        $this->createIndex('dateRegistrationStart', 'elections', ['dateRegistrationStart']);
        $this->createIndex('dateRegistrationEnd', 'elections', ['dateRegistrationEnd']);
        $this->createIndex('dateVotingStart', 'elections', ['dateVotingStart']);
        $this->createIndex('dateVotingEnd', 'elections', ['dateVotingEnd']);
        $this->createIndex('dateResultsPublished', 'elections', ['dateResultsPublished']);
        $this->addForeignKey('parentElectionsIdRef', 'elections', ['parentId'], 'elections', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('parentElectionsIdRef', 'elections');
        $this->dropIndex('whomElections', 'elections');
        $this->dropIndex('whoElections', 'elections');
        $this->dropIndex('parentElectionsId', 'elections');
        $this->dropIndex('dateRegistrationStart', 'elections');
        $this->dropIndex('dateRegistrationEnd', 'elections');
        $this->dropIndex('dateVotingStart', 'elections');
        $this->dropIndex('dateVotingEnd', 'elections');
        $this->dropIndex('dateResultsPublished', 'elections');
        $this->dropTable('elections');
    }

}
