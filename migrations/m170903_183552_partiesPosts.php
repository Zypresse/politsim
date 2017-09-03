<?php

use yii\db\Migration;

class m170903_183552_partiesPosts extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('partiesPosts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'partyId' => $this->integer()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'powers' => $this->integer(4)->unsigned()->notNull(),
            'appointmentType' => $this->integer(2)->unsigned()->notNull(),
            'successorId' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('partyIdPosts', 'partiesPosts', ['partyId']);
        $this->createIndex('userIdPosts', 'partiesPosts', ['userId']);
        $this->createIndex('successorId', 'partiesPosts', ['successorId']);
        
    }

    public function safeDown()
    {
        $this->dropTable('partiesPosts');
    }

}
