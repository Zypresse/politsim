<?php

use yii\db\Migration;

class m170202_141027_update_pops_vacancies extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('nations');
        
        $this->renameTable('pops', 'tmpPops');
        $this->createTable('pops', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            'count' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'classId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'nationId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'ideologies' => 'TEXT NOT NULL',
            'religions' => 'TEXT NOT NULL',
            'genders' => 'TEXT NOT NULL',
            'ages' => 'TEXT NOT NULL',
            'contentmentLow' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'contentmentMiddle' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'contentmentHigh' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'agression' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->execute("
            INSERT INTO pops 
            (id, count, classId, nationId, tileId, ideologies, religions, genders, ages, agression, consciousness, utr)
            SELECT 
            NULL, count, classId, nationId, tileId, ideologies, religions, genders, ages, agression, consciousness, utr
            FROM `tmpPops`
        ");
        $this->dropTable('tmpPops');
        $this->createIndex('popsUnique', 'pops', ['tileId', 'classId', 'nationId'], true);
        $this->createIndex('popsTile', 'pops', ['tileId']);
        $this->createIndex('popsTileClass', 'pops', ['tileId', 'classId']);
        $this->createIndex('popsUTR', 'pops', ['utr'], true);
        
        
        $this->dropTable('popsVacancies');
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

        $this->createTable('vacanciesToPops', [
            'vacancyId' => 'UNSIGNED INTEGER REFERENCES vacancies(id) NOT NULL',
            'popId' => 'UNSIGNED INTEGER REFERENCES pops(id) NOT NULL',
        ]);
        $this->createIndex('vacanciesToPopsUnique', 'vacanciesToPops', ['vacancyId', 'popId'], true);
        
    }

    public function safeDown()
    {
                
        $this->renameTable('pops', 'tmpPops');
        $this->createTable('pops', [
            'count' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'classId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'nationId' => 'UNSIGNED INTEGER(4) REFERENCES nations(id) NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'ideologies' => 'TEXT NOT NULL',
            'religions' => 'TEXT NOT NULL',
            'genders' => 'TEXT NOT NULL',
            'ages' => 'TEXT NOT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'agression' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'dateLastWageGet' => 'UNSIGNED INTEGER DEFAULT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->execute("
            INSERT INTO pops 
            (count, classId, nationId, tileId, ideologies, religions, genders, ages, agression, consciousness, utr)
            SELECT 
            count, classId, nationId, tileId, ideologies, religions, genders, ages, agression, consciousness, utr
            FROM `tmpPops`
        ");
        $this->dropTable('tmpPops');
        $this->createIndex('popsUnique', 'pops', ['tileId', 'classId', 'nationId'], true);
        
        $this->dropTable('vacancies');
        $this->createTable('popsVacancies', [
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
        $this->createIndex('popsVacanciesPrimary', 'popsVacancies', ['objectId', 'popClassId'], true);
        
        $this->dropTable('vacanciesToPops');
        
        $this->createTable('nations', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'groupId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'agressionBase' => 'REAL NOT NULL DEFAULT 0',
            'consciousnessBase' => 'REAL NOT NULL DEFAULT 0',
        ]);
        $this->createIndex('nationsName', 'nations', ['name'], true);
        $this->createIndex('nationsGroup', 'nations', ['groupId']);
    }
    
}
