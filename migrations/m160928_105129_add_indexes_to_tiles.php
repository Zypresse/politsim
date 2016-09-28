<?php

use yii\db\Migration;

class m160928_105129_add_indexes_to_tiles extends Migration
{
    public function up()
    {
        $this->createIndex('x', 'tiles', ['x']);
        $this->createIndex('y', 'tiles', ['y']);
        $this->createIndex('isLand', 'tiles', ['isLand']);
        $this->createIndex('isWater', 'tiles', ['isWater']);
        $this->createIndex('regionId', 'tiles', 'regionId');
        $this->createIndex('cityId', 'tiles', 'cityId');
        $this->createIndex('electoralDistrictId', 'tiles', 'electoralDistrictId');
    }

    public function down()
    {
        $this->dropIndex('x', 'tiles');
        $this->dropIndex('y', 'tiles');
        $this->dropIndex('isLand', 'tiles');
        $this->dropIndex('isWater', 'tiles');
        $this->dropIndex('regionId', 'tiles');
        $this->dropIndex('cityId', 'tiles');
        $this->dropIndex('electoralDistrictId', 'tiles');
    }

}
