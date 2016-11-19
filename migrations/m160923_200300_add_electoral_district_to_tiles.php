<?php

use yii\db\Migration;

class m160923_200300_add_electoral_district_to_tiles extends Migration
{
    public function safeUp()
    {
        $this->dropIndex('tilesXY', 'tiles');
        $this->renameTable('tiles', 'tmp-tiles');
        
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
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL',
            'electoralDistrictId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) DEFAULT NULL'
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);
        
        $this->execute("
            INSERT INTO tiles 
            (id,x,y,lat,lon,isWater,isLand,population,regionId,cityId)
            SELECT id,x,y,lat,lon,isWater,isLand,population,regionId,cityId FROM `tmp-tiles`
        ");
        $this->dropTable('tmp-tiles');
        
        $this->dropTable('electoral-districts-to-tiles');
    }

    public function safeDown()
    {
        $this->dropIndex('tilesXY', 'tiles');
        $this->renameTable('tiles', 'tmp-tiles');
        
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
        
        $this->execute("
            INSERT INTO tiles 
            (id,x,y,lat,lon,isWater,isLand,population,regionId,cityId)
            SELECT id,x,y,lat,lon,isWater,isLand,population,regionId,cityId FROM `tmp-tiles`
        ");
        $this->dropTable('tmp-tiles');
        
        
        $this->createTable('electoral-districts-to-tiles', [
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
        ]);
        $this->createIndex('electoralDistrictsToTiles', 'electoral-districts-to-tiles', ['districtId', 'tileId'], true);
    }

}
