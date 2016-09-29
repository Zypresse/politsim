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
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/regions.json'));
        array_pop($data);
        $this->batchInsert('regions', ['id', 'name', 'nameShort', 'population'], $data);
        
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/cities.json'));
        array_pop($data);
        $this->batchInsert('cities', ['name', 'nameShort', 'regionId', 'population'], $data);
        
        /* @var $region app\models\Region */
        foreach (app\models\Region::findAll() as $region) {
            /* @var $city app\models\City */
            $city = $region->getCities()->orderBy(['population' => SORT_DESC])->one();
            if ($city) {
                $region->link('city', $city);
            }
        }
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
