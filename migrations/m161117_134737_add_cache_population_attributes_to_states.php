<?php

use yii\db\Migration;

class m161117_134737_add_cache_population_attributes_to_states extends Migration
{
    
    public function safeUp()
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
            
            'classes' => 'TEXT DEFAULT NULL',
            'nations' => 'TEXT DEFAULT NULL',
            'ideologies' => 'TEXT DEFAULT NULL',
            'religions' => 'TEXT DEFAULT NULL',
            'genders' => 'TEXT DEFAULT NULL',
            'ages' => 'TEXT DEFAULT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'agression' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
    }

    public function safeDown()
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
    }
    
}
