<?php

use yii\db\Migration;

class m160825_064833_states extends Migration
{
    public function up()
    {
        $this->createTable('states', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) NOT NULL',
            'mapColor' => 'VARCHAR(6) DEFAULT NULL',
            'govermentFormId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'stateStructureId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('states');
    }

}
