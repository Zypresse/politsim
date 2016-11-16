<?php

use yii\db\Migration;

class m160929_113945_default_regions_and_cities extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('regions');
        $this->createTable('regions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) DEFAULT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        
    }

    public function safeDown()
    {
        $this->dropTable('regions');
        $this->createTable('regions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        
        $this->delete('cities');
    }
}
