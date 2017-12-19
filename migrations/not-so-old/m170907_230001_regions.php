<?php

use yii\db\Migration;

class m170907_230001_regions extends Migration
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
            'usersCount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersFame' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'implodedTo' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
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
        $this->addForeignKey('regionsCityIdRef', 'regions', ['cityId'], 'cities', ['id']);
        $this->addForeignKey('regionsImplodedToRef', 'regions', ['implodedTo'], 'regions', ['id']);
        
        $this->addForeignKey('citiesRegionIdRef', 'cities', ['regionId'], 'regions', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('citiesRegionIdRef', 'cities');
        
        $this->dropForeignKey('regionsCityIdRef', 'regions');
        $this->dropForeignKey('regionsImplodedToRef', 'regions');
        $this->dropIndex('stateIdRegions', 'regions');
        $this->dropIndex('cityIdRegions', 'regions');
        $this->dropIndex('nameRegions', 'regions');
        $this->dropIndex('nameShortRegions', 'regions');
        $this->dropIndex('populationRegions', 'regions');
        $this->dropIndex('usersCountRegions', 'regions');
        $this->dropIndex('usersFameRegions', 'regions');
        $this->dropIndex('dateCreatedRegions', 'regions');
        $this->dropIndex('dateDeletedRegions', 'regions');
        $this->dropIndex('implodedToRegions', 'regions');
        $this->dropIndex('utrRegions', 'regions');
        $this->dropTable('regions');
    }

}
