<?php

use yii\db\Migration;

class m170203_140751_default_currency extends Migration
{
    public function up()
    {
        $this->insert('currencies', [
            'id' => 0,
            'emissionerId' => 0,
            'name' => 'Международная валюта',
            'nameShort' => 'у.е.',
            'inflation' => 0,
            'exchangeRate' => 1,
            'exchangeRateReal' => 1,
        ]);
    }

    public function down()
    {
        $this->truncateTable('currencies');
    }
}
