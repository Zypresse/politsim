<?php

use yii\db\Migration;

class m170903_153536_agenciesPosts extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('agenciesPosts', [
            'id' => $this->primaryKey()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'partyId' => $this->integer()->unsigned()->null(),
            'userId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'utr' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('stateIdAgenciesPosts', 'agenciesPosts', ['stateId']);
        $this->createIndex('partyIdAgenciesPosts', 'agenciesPosts', ['partyId']);
        $this->createIndex('userIdAgenciesPosts', 'agenciesPosts', ['userId']);
        $this->createIndex('nameAgenciesPosts', 'agenciesPosts', ['name']);
        $this->createIndex('nameShortAgenciesPosts', 'agenciesPosts', ['nameShort']);
        $this->createIndex('utrAgenciesPosts', 'agenciesPosts', ['utr'], true);
    }

    public function safeDown()
    {
        $this->dropTable('agenciesPosts');
    }

}
