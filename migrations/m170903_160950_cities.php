<?php

use yii\db\Migration;

class m170903_160950_cities extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('cities', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'regionId' => $this->integer()->unsigned()->null(),
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
            'utr' => $this->integer()->null(),
        ]);
        $this->createIndex('regionIdCities', 'cities', ['regionId']);
        $this->createIndex('nameCities', 'cities', ['name']);
        $this->createIndex('nameShortCities', 'cities', ['nameShort']);
        $this->createIndex('populationCities', 'cities', ['population']);
        $this->createIndex('contentmentCities', 'cities', ['contentment']);
        $this->createIndex('agressionCities', 'cities', ['agression']);
        $this->createIndex('consciousnessCities', 'cities', ['consciousness']);
        $this->createIndex('usersCountCities', 'cities', ['usersCount']);
        $this->createIndex('usersFameCities', 'cities', ['usersFame']);
        $this->createIndex('utrCities', 'cities', ['utr']);
    }

    public function safeDown()
    {
        $this->dropTable('cities');
    }
    
}
