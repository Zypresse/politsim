<?php

use yii\db\Migration;

class m180313_181801_create_cities_table extends Migration
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
            'usersCount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersFame' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'utr' => $this->integer()->unique()->null(),
        ]);
        $this->createIndex('regionIdCities', 'cities', ['regionId']);
        $this->createIndex('nameCities', 'cities', ['name']);
        $this->createIndex('nameShortCities', 'cities', ['nameShort']);
        $this->createIndex('populationCities', 'cities', ['population']);
        $this->createIndex('usersCountCities', 'cities', ['usersCount']);
        $this->createIndex('usersFameCities', 'cities', ['usersFame']);
        $this->createIndex('utrCities', 'cities', ['utr'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('regionIdCities', 'cities');
        $this->dropIndex('nameCities', 'cities');
        $this->dropIndex('nameShortCities', 'cities');
        $this->dropIndex('populationCities', 'cities');
        $this->dropIndex('usersCountCities', 'cities');
        $this->dropIndex('usersFameCities', 'cities');
        $this->dropIndex('utrCities', 'cities');
        $this->dropTable('cities');
    }
    
}
