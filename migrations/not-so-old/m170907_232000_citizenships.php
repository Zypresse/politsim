<?php

use yii\db\Migration;

class m170907_232000_citizenships extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('citizenships', [
            'userId' => $this->integer()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateApproved' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('user2state', 'citizenships', ['userId', 'stateId'], true);
        $this->createIndex('dateCreatedCitizenships', 'citizenships', ['dateCreated']);
        $this->createIndex('dateApprovedCitizenships', 'citizenships', ['dateApproved']);
        $this->addForeignKey('user2stateStateRef', 'citizenships', ['stateId'], 'states', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('user2stateStateRef', 'citizenships');
        $this->dropIndex('user2state', 'citizenships');
        $this->dropIndex('dateCreatedCitizenships', 'citizenships');
        $this->dropIndex('dateApprovedCitizenships', 'citizenships');
        $this->dropTable('citizenships');
    }

}
