<?php

use yii\db\Migration;

class m170903_184355_regions extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('regions', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->null(),
            'cityId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'flag' => $this->string(255)->null(),
            'anthem' => $this->string(255)->null(),
            'population' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'contentment' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'agression' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'consciousness' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'usersCount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersFame' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'implodedTo' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('stateIdRegions', 'regions', ['stateId']);
        $this->createIndex('cityIdRegions', 'regions', ['cityId']);
        $this->createIndex('nameRegions', 'regions', ['name']);
        $this->createIndex('nameShortRegions', 'regions', ['nameShort']);
        $this->createIndex('populationRegions', 'regions', ['population']);
        $this->createIndex('usersCountRegions', 'regions', ['usersCount']);
        $this->createIndex('usersFameRegions', 'regions', ['usersFame']);
        $this->createIndex('dateCreatedRegions', 'regions', ['dateCreated']);
        $this->createIndex('dateDeletedRegions', 'regions', ['dateDeleted']);
        $this->createIndex('implodedToRegions', 'regions', ['implodedTo']);
        $this->createIndex('utrRegions', 'regions', ['utr'], true);
    }

    public function safeDown()
    {
        $this->dropTable('regions');
    }

}
