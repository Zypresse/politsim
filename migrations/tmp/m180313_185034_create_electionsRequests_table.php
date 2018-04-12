<?php

use yii\db\Migration;

class m180313_185034_create_electionsRequests_table extends Migration
{
    
    public function safeUp()
    {
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
        $this->createIndex('Elections2Object', 'electionsRequests', ['electionId', 'objectId'], true);
        $this->addForeignKey('electionIdElectionsRequestsRef', 'electionsRequests', ['electionId'], 'elections', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('electionIdElectionsRequestsRef', 'electionsRequests');
        $this->dropIndex('electionIdElectionsRequests', 'electionsRequests');
        $this->dropIndex('typeElectionsRequests', 'electionsRequests');
        $this->dropIndex('objectIdElectionsRequests', 'electionsRequests');
        $this->dropIndex('variantElectionsRequests', 'electionsRequests');
        $this->dropIndex('dateCreatedElectionsRequests', 'electionsRequests');
        $this->dropIndex('dateApprovedElectionsRequests', 'electionsRequests');
        $this->dropIndex('Elections2Object', 'electionsRequests');
        $this->dropTable('electionsRequests');
    }

}
