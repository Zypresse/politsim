<?php

use yii\db\Migration;

class m160909_135056_parties_update extends Migration
{
    public function up()
    {
        $this->dropTable('parties');
        
        $this->createTable('parties', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'text' => 'TEXT DEFAULT NULL',
            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'membersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES `parties-posts`(id) DEFAULT NULL',
            // 0 закрытая
            // 1 по заявкам
            // 2 свободно
            'joiningRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            // способ формирования партийного списка
            // 1 лидером
            // 2 праймериз
            'listCreationRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

    }

    public function down()
    {
        $this->dropTable('parties');
        
        $this->createTable('parties', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'ideology' => 'UNSIGNED INTEGER(3) NOT NULL',
            'text' => 'TEXT NOT NULL',
            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'membersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES `parties-posts`(id) NOT NULL',
            // 0 закрытая
            // 1 по заявкам
            // 2 свободно
            'joiningRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            // способ формирования партийного списка
            // 1 лидером
            // 2 праймериз
            'listCreationRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
    }

}
