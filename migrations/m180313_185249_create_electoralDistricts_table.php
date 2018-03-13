<?php

use yii\db\Migration;

class m180313_185249_create_electoralDistricts_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('electoralDistricts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'polygon' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('stateIdDistricts', 'electoralDistricts', ['stateId']);
        $this->createIndex('nameDistricts', 'electoralDistricts', ['name']);
        $this->createIndex('nameShortDistricts', 'electoralDistricts', ['nameShort']);
        $this->addForeignKey('stateIdDistricts', 'electoralDistricts', ['stateId'], 'states', ['id']);
        
        $this->addForeignKey('districtIdVotesUsersRef', 'electionsVotesUsers', ['districtId'], 'electoralDistricts', ['id']);
        $this->addForeignKey('districtIdVotesPopsRef', 'electionsVotesPops', ['districtId'], 'electoralDistricts', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('districtIdVotesUsersRef', 'electionsVotesUsers');
        $this->dropForeignKey('districtIdVotesPopsRef', 'electionsVotesPops');
        
        $this->dropForeignKey('stateIdDistricts', 'electoralDistricts');
        $this->dropIndex('stateIdDistricts', 'electoralDistricts');
        $this->dropIndex('nameDistricts', 'electoralDistricts');
        $this->dropIndex('nameShortDistricts', 'electoralDistricts');
        $this->dropTable('electoralDistricts');
    }

}
