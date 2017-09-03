<?php

use yii\db\Migration;

class m170903_185407_states extends Migration
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
            'contentment' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'agression' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'consciousness' => $this->double()->unsigned()->notNull()->defaultValue(0),
            'usersCount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'usersFame' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'dateCreated' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->null(),
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
    }

    public function safeDown()
    {
        $this->dropTable('states');
    }

}
