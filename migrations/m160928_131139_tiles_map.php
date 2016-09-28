<?php

use yii\db\Migration;

class m160928_131139_tiles_map extends Migration
{

    public function safeUp()
    {
        $this->delete('tiles');
        
        for ($i = 0; $i < 36; $i++) {
            $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/tiles'.$i.'.json'));
            array_pop($data);
            $this->batchInsert('tiles', ['x','y','lat','lon', 'isWater', 'isLand'], $data);
        }
    }

    public function safeDown()
    {
        $this->delete('tiles');
    }
}
