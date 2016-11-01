<?php

use yii\db\Migration;

class m160928_131139_tiles_map extends Migration
{

    public function safeUp()
    {
        $this->delete('tiles');
        
        for ($i = 0; $i < 36; $i++) {
            $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/tiles/part'.$i.'.json'));
            array_pop($data);
            foreach (array_chunk($data, 500) as $tiles) {
                $this->batchInsert('tiles', ['x','y','lat','lon', 'isWater', 'isLand', 'regionId', 'cityId'], $tiles);
            }
        }
    }

    public function safeDown()
    {
//        $this->delete('tiles');
    }
}
