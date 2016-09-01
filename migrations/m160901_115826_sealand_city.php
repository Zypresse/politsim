<?php

use yii\db\Migration;

class m160901_115826_sealand_city extends Migration
{
    public function up()
    {
        $this->insert('cities', [
            'id' => 1,
            'regionId' => 1,
            'name' => 'Sealand',
            'nameShort' => 'Seal.',
            'population' => 11,
            'usersCount' => 2
        ]);
        $this->insert('regions', [
            'id' => 1,
            'stateId' => 1,
            'cityId' => 1,
            'name' => 'Sealand',
            'nameShort' => 'Seal.',
            'population' => 11,
            'usersCount' => 2
        ]);
    }

    public function down()
    {
        $this->truncateTable('cities');
    }

}
