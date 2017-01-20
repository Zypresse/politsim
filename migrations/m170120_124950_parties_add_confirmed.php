<?php

use yii\db\Migration;

class m170120_124950_parties_add_confirmed extends Migration
{
    
    public function safeUp()
    {
        $this->renameTable('parties', 'tmpParties');
        $this->createTable('parties', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'text' => 'TEXT DEFAULT NULL',
            'textHTML' => 'TEXT DEFAULT NULL',
            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'membersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES `partiesPosts`(id) DEFAULT NULL',
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
            'dateConfirmed' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
        
        $this->execute("
            INSERT INTO parties 
            (id, stateId, name, nameShort, flag, anthem, ideologyId, text, textHTML, fame, trust, success, membersCount, leaderPostId, joiningRules, listCreationRules, utr, dateCreated, dateDeleted)
            SELECT id, stateId, name, nameShort, flag, anthem, ideologyId, text, textHTML, fame, trust, success, membersCount, leaderPostId, joiningRules, listCreationRules, utr, dateCreated, dateDeleted FROM `tmpParties`
        ");
        $this->dropTable('tmpParties');
    }

    public function safeDown()
    {
        $this->renameTable('parties', 'tmpParties');
        $this->createTable('parties', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT DEFAULT NULL',
            'anthem' => 'TEXT DEFAULT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'text' => 'TEXT DEFAULT NULL',
            'textHTML' => 'TEXT DEFAULT NULL',
            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'membersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES `partiesPosts`(id) DEFAULT NULL',
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
        
        $this->execute("
            INSERT INTO parties 
            (id, stateId, name, nameShort, flag, anthem, ideologyId, text, textHTML, fame, trust, success, membersCount, leaderPostId, joiningRules, listCreationRules, utr, dateCreated, dateDeleted)
            SELECT id, stateId, name, nameShort, flag, anthem, ideologyId, text, textHTML, fame, trust, success, membersCount, leaderPostId, joiningRules, listCreationRules, utr, dateCreated, dateDeleted FROM `tmpParties`
        ");
        $this->dropTable('tmpParties');
    }
    
}
