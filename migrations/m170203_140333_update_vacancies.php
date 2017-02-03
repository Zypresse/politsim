<?php

use yii\db\Migration;

class m170203_140333_update_vacancies extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('vacancies');
        $this->createTable('vacancies', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'popClassId' => 'UNSIGNED INTEGER(2) NOT NULL',

            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'wage' => 'UNSIGNED REAL NOT NULL',

            'countAll' => 'UNSIGNED INTEGER NOT NULL',
            'countFree' => 'UNSIGNED INTEGER NOT NULL',
        ]);
        $this->createIndex('vacanciesUnique', 'vacancies', ['objectId', 'popClassId'], true);

    }

    public function safeDown()
    {
        
        $this->dropTable('vacancies');
        $this->createTable('vacancies', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'popClassId' => 'UNSIGNED INTEGER(2) NOT NULL',

            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'wage' => 'UNSIGNED REAL NOT NULL',

            'countAll' => 'UNSIGNED INTEGER NOT NULL',
            'countFree' => 'UNSIGNED INTEGER NOT NULL',

            'popNationId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'popNationGroupId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popReligionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popGenderId' => 'UNSIGNED INTEGER(1) DEFAULT NULL',
            'popAgeMin' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popAgeMax' => 'UNSIGNED INTEGER(3) DEFAULT NULL'
        ]);
        $this->createIndex('vacanciesUnique', 'vacancies', ['objectId', 'popClassId'], true);

    }
    
}
