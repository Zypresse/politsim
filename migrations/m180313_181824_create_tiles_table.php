<?php

use yii\db\Migration;

class m180313_181824_create_tiles_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('tiles', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'x' => $this->smallInteger(6)->notNull(),
            'y' => $this->smallInteger(6)->notNull(),
            'lat' => $this->integer()->notNull(),
            'lon' => $this->integer()->notNull(),
            'biome' => $this->smallinteger(2)->unsigned()->notNull()->defaultValue(0),
            'population' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'regionId' => $this->integer()->unsigned()->null(),
            'cityId' => $this->integer()->unsigned()->null(),
            'districtId' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);
        $this->createIndex('tilesLatLon', 'tiles', ['lat', 'lon'], true);
        $this->createIndex('tilesBiome', 'tiles', ['biome']);
        $this->createIndex('populationTiles', 'tiles', 'population');
        $this->createIndex('tilesRegionId', 'tiles', ['regionId']);
        $this->createIndex('tilesСityId', 'tiles', ['cityId']);
        $this->createIndex('tilesDistrictId', 'tiles', ['districtId']);
        
        $this->addForeignKey('tilesRegion', 'tiles', ['regionId'], 'regions', ['id']);
        $this->addForeignKey('tilesCity', 'tiles', ['cityId'], 'cities', ['id']);
        $this->addForeignKey('usersTile', 'users', ['tileId'], 'tiles', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('usersTile', 'users');
        $this->dropForeignKey('tilesRegion', 'tiles');
        $this->dropForeignKey('tilesCity', 'tiles');
        
        $this->dropIndex('tilesXY', 'tiles');
        $this->dropIndex('tilesLatLon', 'tiles');
        $this->dropIndex('tilesBiome', 'tiles');
        $this->dropIndex('populationTiles', 'tiles');
        $this->dropIndex('tilesRegionId', 'tiles');
        $this->dropIndex('tilesСityId', 'tiles');
        $this->dropIndex('tilesDistrictId', 'tiles');
        
        $this->dropTable('tiles');
    }

}
