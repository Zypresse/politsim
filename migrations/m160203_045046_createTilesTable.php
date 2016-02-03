<?php

use yii\db\Migration,
    app\models\tiles\TileFactory;

class m160203_045046_createTilesTable extends Migration
{
    public function up()
    {
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'w_lat' => 'REAL NOT NULL',
            'w_lng' => 'REAL NOT NULL',
            'nw_lat' => 'REAL NOT NULL',
            'nw_lng' => 'REAL NOT NULL',
            'ne_lat' => 'REAL NOT NULL',
            'ne_lng' => 'REAL NOT NULL',
            'e_lat' => 'REAL NOT NULL',
            'e_lng' => 'REAL NOT NULL',
            'se_lat' => 'REAL NOT NULL',
            'se_lng' => 'REAL NOT NULL',
            'sw_lat' => 'REAL NOT NULL',
            'sw_lng' => 'REAL NOT NULL',
            'center_lat' => 'REAL NOT NULL',
            'center_lng' => 'REAL NOT NULL',
            'is_water' => 'INTEGER NOT NULL DEFAULT 1',
            'is_land' => 'INTEGER NOT NULL DEFAULT 0',
            'is_mountains' => 'INTEGER NOT NULL DEFAULT 0',
            'population' => 'INTEGER NOT NULL DEFAULT 0',
            'region_id' => 'INTEGER DEFAULT NULL'
        ]);
        
        $this->createIndex('coords', 'tiles', ['x','y'], true);
        /*
        for ($x = -866; $x <= 866; $x++) {
            for ($y = -1200; $y <= 1200; $y++) {
                $tile = TileFactory::generate($x, $y);
                if (!$tile->save()) {
                    var_dump($tile->getErrors());
                    return false;
                }
                echo "Saved tile {$x}x{$y}".PHP_EOL;
            }
        }*/
    }

    public function down()
    {
        $this->dropTable('tiles');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
