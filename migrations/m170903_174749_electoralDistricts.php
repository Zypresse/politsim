<?php

use yii\db\Migration;

class m170903_174749_electoralDistricts extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('electoralDistricts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
        ]);
        $this->createIndex('stateIdDistricts', 'electoralDistricts', ['stateId']);
        $this->createIndex('nameDistricts', 'electoralDistricts', ['name']);
        $this->createIndex('nameShortDistricts', 'electoralDistricts', ['nameShort']);
    }

    public function safeDown()
    {
        $this->dropTable('electoralDistricts');
    }

}
