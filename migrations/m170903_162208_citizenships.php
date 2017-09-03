<?php

use yii\db\Migration;

class m170903_162208_citizenships extends Migration
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
    }

    public function safeDown()
    {
        $this->dropTable('citizenships');
    }

}
