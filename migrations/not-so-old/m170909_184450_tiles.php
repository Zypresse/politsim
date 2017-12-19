<?php

use yii\db\Migration;

class m170909_184450_tiles extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('tiles', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'x' => $this->smallInteger(6)->notNull(),
            'y' => $this->smallInteger(6)->notNull(),
            'lat' => $this->double()->notNull(),
            'lon' => $this->double()->notNull(),
            'isWater' => $this->boolean()->notNull()->defaultValue(false),
            'isLand' => $this->boolean()->notNull()->defaultValue(false),
            'population' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'regionId' => $this->integer()->unsigned()->null(),
            'cityId' => $this->integer()->unsigned()->null(),
            'districtId' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);
        $this->createIndex('tilesLatLon', 'tiles', ['lat', 'lon'], true);
        $this->createIndex('tilesIsWater', 'tiles', ['isWater']);
        $this->createIndex('tileIsLand', 'tiles', ['isLand']);
        $this->createIndex('populationTiles', 'tiles', 'population');
        $this->createIndex('tilesRegionId', 'tiles', ['regionId']);
        $this->createIndex('tilesСityId', 'tiles', ['cityId']);
        $this->createIndex('tilesDistrictId', 'tiles', ['districtId']);
        
        $this->addForeignKey('tilesRegion', 'tiles', ['regionId'], 'regions', ['id']);
        $this->addForeignKey('tilesCity', 'tiles', ['cityId'], 'cities', ['id']);
        $this->addForeignKey('tilesDistrict', 'tiles', ['districtId'], 'electoralDistricts', ['id']);
        
        $this->addForeignKey('usersTile', 'users', ['tileId'], 'tiles', ['id']);
        $this->addForeignKey('electionsVotesPopsTileRef', 'electionsVotesPops', ['tileId'], 'tiles', ['id']);
        $this->addForeignKey('electionsVotesUsersTileRef', 'electionsVotesUsers', ['tileId'], 'tiles', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('usersTile', 'users');
        $this->dropForeignKey('electionsVotesPopsTileRef', 'electionsVotesPops');
        $this->dropForeignKey('electionsVotesUsersTileRef', 'electionsVotesUsers');
        
        $this->dropForeignKey('tilesRegion', 'tiles');
        $this->dropForeignKey('tilesCity', 'tiles');
        $this->dropForeignKey('tilesDistrict', 'tiles');
        
        $this->dropIndex('tilesXY', 'tiles');
        $this->dropIndex('tilesLatLon', 'tiles');
        $this->dropIndex('tilesIsWater', 'tiles');
        $this->dropIndex('tileIsLand', 'tiles');
        $this->dropIndex('populationTiles', 'tiles');
        $this->dropIndex('tilesRegionId', 'tiles');
        $this->dropIndex('tilesСityId', 'tiles');
        $this->dropIndex('tilesDistrictId', 'tiles');
        
        $this->dropTable('tiles');
    }

}
