<?php

use yii\db\Migration;

class m161222_120644_elections_votes_users_fix extends Migration
{

    public function up()
    {

        $this->dropTable('electionsVotesUsers');
        $this->createTable('electionsVotesUsers', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoralDistricts`(id) DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsersPrimary', 'electionsVotesUsers', ['electionId', 'userId'], true);
    }

    public function down()
    {

        $this->dropTable('electionsVotesUsers');
        $this->createTable('electionsVotesUsers', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoralDistricts`(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsersPrimary', 'electionsVotesUsers', ['electionId', 'userId'], true);
    }

}
