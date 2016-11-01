<?php

use yii\db\Migration;

class m160828_120325_sealand_state extends Migration
{
    
    public function up()
    {
        
        $this->dropTable('states');
        $this->createTable('states', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL',
            'mapColor' => 'VARCHAR(6) DEFAULT NULL',
            'govermentFormId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'stateStructureId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
//        
//        $this->insert('states', [
//            'id' => 1,
//            'cityId' => 1,
//            'name' => 'Principality of Sealand',
//            'nameShort' => 'SEA',
//            'flag' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Flag_of_Sealand.svg/320px-Flag_of_Sealand.svg.png',
//            'anthem' => 'https://soundcloud.com/sien69/e-mare-libertas',
//            'mapColor' => 'ff0000',
//            'population' => 11,
//            'dateCreated' => time()
//        ]);
    }

    public function down()
    {
        $this->dropTable('states');
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

}
