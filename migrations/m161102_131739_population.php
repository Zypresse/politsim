<?php

use yii\db\Migration;

class m161102_131739_population extends Migration
{
    public function safeUp()
    {
        $this->dropTable('pops');
        
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
        $this->createIndex('popsUnique', 'pops', ['tileId', 'classId', 'nationId'], true);
        
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

    public function safeDown()
    {
        $this->dropTable('nations');
        $this->dropTable('pops');
        $this->createTable('pops', [
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'workplaceId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'livingplaceId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'classId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'nationId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'religionId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'genderId' => 'UNSIGNED INTEGER(1) NOT NULL',
            'age' => 'UNSIGNED INTEGER(3) NOT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'dateLastWageGet' => 'UNSIGNED INTEGER DEFAULT NULL',
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('popsUnique', 'pops', ['tileId', 'workplaceId', 'livingplaceId', 'classId', 'nationId', 'ideologyId', 'religionId', 'genderId', 'age'], true);
    }
}
