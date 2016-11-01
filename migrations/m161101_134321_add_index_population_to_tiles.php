<?php

use yii\db\Migration;

class m161101_134321_add_index_population_to_tiles extends Migration
{
    public function up()
    {
        $this->createIndex('population', 'tiles', ['population']);
    }

    public function down()
    {        
        $this->dropIndex('population', 'tiles');
    }
}
