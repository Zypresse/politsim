<?php

use yii\db\Migration;

class m180313_181803_create_states_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('states', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'flag' => $this->string(255)->null(),
            'anthem' => $this->string(255)->null(),
            'cityId' => $this->integer()->unsigned()->null(),
            'mapColor' => $this->string(6)->null(),
            'govermentFormId' => $this->integer(2)->unsigned()->null(),
            'stateStructureId' => $this->integer(1)->unsigned()->null(),
            'population' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersCount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersFame' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'polygon' => 'JSONB DEFAULT NULL',
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('nameStates', 'states', ['name']);
        $this->createIndex('nameShortStates', 'states', ['nameShort']);
        $this->createIndex('cityIdStates', 'states', ['cityId']);
        $this->createIndex('govermentFormIdStates', 'states', ['govermentFormId']);
        $this->createIndex('stateStructureIdStates', 'states', ['stateStructureId']);
        $this->createIndex('populationStates', 'states', ['population']);
        $this->createIndex('usersCountStates', 'states', ['usersCount']);
        $this->createIndex('usersFameStates', 'states', ['usersFame']);
        $this->createIndex('dateCreatedStates', 'states', ['dateCreated']);
        $this->createIndex('dateDeletedStates', 'states', ['dateDeleted']);
        $this->createIndex('utrStates', 'states', ['utr'], true);
        $this->addForeignKey('cityIdStatesRef', 'states', ['cityId'], 'cities', ['id']);
        
        $this->addForeignKey('regionsStateIdRef', 'regions', ['stateId'], 'states', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('regionsStateIdRef', 'regions');
        
        $this->dropForeignKey('cityIdStatesRef', 'states');
        $this->dropIndex('nameStates', 'states');
        $this->dropIndex('nameShortStates', 'states');
        $this->dropIndex('cityIdStates', 'states');
        $this->dropIndex('govermentFormIdStates', 'states');
        $this->dropIndex('stateStructureIdStates', 'states');
        $this->dropIndex('populationStates', 'states');
        $this->dropIndex('usersCountStates', 'states');
        $this->dropIndex('usersFameStates', 'states');
        $this->dropIndex('dateCreatedStates', 'states');
        $this->dropIndex('dateDeletedStates', 'states');
        $this->dropIndex('utrStates', 'states');
        $this->dropTable('states');
    }

}
