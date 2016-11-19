<?php

use yii\db\Migration;

class m161116_082840_add_cache_population_attributes_to_regions_and_cities extends Migration
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
            
            'classes' => 'TEXT DEFAULT NULL',
            'nations' => 'TEXT DEFAULT NULL',
            'ideologies' => 'TEXT DEFAULT NULL',
            'religions' => 'TEXT DEFAULT NULL',
            'genders' => 'TEXT DEFAULT NULL',
            'ages' => 'TEXT DEFAULT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'agression' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        
        $this->dropTable('cities');
        $this->createTable('cities', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            
            'classes' => 'TEXT DEFAULT NULL',
            'nations' => 'TEXT DEFAULT NULL',
            'ideologies' => 'TEXT DEFAULT NULL',
            'religions' => 'TEXT DEFAULT NULL',
            'genders' => 'TEXT DEFAULT NULL',
            'ages' => 'TEXT DEFAULT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'agression' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            
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
        
        $this->dropTable('cities');
        $this->createTable('cities', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'            
        ]);
    }
}
