<?php

use yii\db\Migration;

class m161220_131230_agency_post_taxpayer extends Migration
{
    public function up()
    {
        $this->dropTable('agenciesPosts');
        $this->createTable('agenciesPosts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('agenciesPosts');
        $this->createTable('agenciesPosts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL'
        ]);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
