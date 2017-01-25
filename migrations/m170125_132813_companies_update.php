<?php

use yii\db\Migration;

class m170125_132813_companies_update extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('companies');
        $this->createTable('companies', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) DEFAULT NULL',
            'mainOfficeId' => 'UNSIGNED INTEGER REFERENCES buildings(id) DEFAULT NULL',
            'directorId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'VARCHAR(255) NOT NULL',
            'efficiencyManagement' => 'UNSIGNED REAL NOT NULL DEFAULT 1',
            'capitalization' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'sharesPrice' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'sharesIssued' => 'UNSIGNED INTEGER NOT NULL',
            'isGoverment' => 'BOOLEAN DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->dropTable('companiesDecisions');
        $this->createTable('companiesDecisions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'companyId' => 'UNSIGNED INTEGER REFERENCES companies(id) NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiatorId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'data' => 'TEXT DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingFinished' => 'UNSIGNED INTEGER NOT NULL',
            'dateFinished' => 'UNSIGNED INTEGER DEFAULT NULL',
            'isApproved' => 'BOOLEAN DEFAULT NULL',
            'votesPlus' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED REAL NOT NULL DEFAULT 0'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('companies');
        $this->createTable('companies', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'mainOfficeId' => 'UNSIGNED INTEGER REFERENCES buildings(id) DEFAULT NULL',
            'directorId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'efficiencyManagement' => 'UNSIGNED REAL NOT NULL DEFAULT 1',
            'sharesIssued' => 'UNSIGNED INTEGER NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER MPT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->dropTable('companiesDecisions');
        $this->createTable('companiesDecisions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'companyId' => 'UNSIGNED INTEGER REFERENCES companies(id) NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiatorId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'data' => 'TEXT DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingEnd' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL',
            'percentAgreement' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'percentDisagreement' => 'UNSIGNED REAL NOT NULL DEFAULT 0'
        ]);
    }
    
}
