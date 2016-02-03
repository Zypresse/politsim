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
            'center_lat' => 'REAL NOT NULL',
            'center_lng' => 'REAL NOT NULL',
            'is_water' => 'INTEGER NOT NULL DEFAULT 1',
            'is_land' => 'INTEGER NOT NULL DEFAULT 0',
            'is_mountains' => 'INTEGER NOT NULL DEFAULT 0',
            'population' => 'INTEGER NOT NULL DEFAULT 0',
            'region_id' => 'INTEGER DEFAULT NULL'
        ]);
        
        $this->createIndex('coords', 'tiles', ['x','y'], true);
        
        for ($x = -866; $x <= 866; $x++) {
            for ($y = -1200; $y <= 1200; $y++) {
                $tile = TileFactory::generate($x, $y);
                if (!$tile->save()) {
                    var_dump($tile->getErrors());
                    return false;
                }
                echo "Saved tile {$x}x{$y}".PHP_EOL;
            }
        }
    }

    public function down()
    {
        $this->dropTable('tiles');
    }
}
