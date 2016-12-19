<?php

use yii\db\Migration;

class m160828_110458_sealand_tile extends Migration
{
    public function up()
    {
        $this->dropTable('regions');
        $this->createTable('regions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
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
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'            
        ]);
        
        $this->dropTable('tiles');        
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'lat' => 'REAL NOT NULL',
            'lon' => 'REAL NOT NULL',
            'isWater' => 'BOOLEAN NOT NULL DEFAULT 1',
            'isLand' => 'BOOLEAN NOT NULL DEFAULT 0',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL'
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);
                
    }

    public function down()
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
        
        $this->dropTable('tiles');        
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'lat' => 'REAL NOT NULL',
            'lon' => 'REAL NOT NULL',
            'isWater' => 'BOOLEAN NOT NULL DEFAULT 1',
            'isLand' => 'BOOLEAN NOT NULL DEFAULT 0',
            'isMountains' => 'BOOLEAN NOT NULL DEFAULT 0',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL'
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);
    }

}
