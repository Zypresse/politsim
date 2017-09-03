<?php

use yii\db\Migration;

class m170903_165521_electionsVotesPops extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('electionsVotesPops', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'electionId' => $this->integer()->unsigned()->notNull(),
            'count' => $this->integer()->unsigned()->notNull(),
            'tileId' => $this->integer()->unsigned()->notNull(),
            'variant' => $this->integer(3)->unsigned()->notNull(),
            'districtId' => $this->integer()->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->createIndex('election2tile2variant', 'electionsVotesPops', ['electionId', 'tileId', 'variant'], true);
        $this->createIndex('electionIdVotesPops', 'electionsVotesPops', ['electionId']);
        $this->createIndex('tileIdVotesPops', 'electionsVotesPops', ['tileId']);
        $this->createIndex('election2varianPops', 'electionsVotesPops', ['electionId', 'variant']);
        $this->createIndex('districtIdVotesPops', 'electionsVotesPops', ['districtId']);
        $this->createIndex('dateCreatedVotesPops', 'electionsVotesPops', ['dateCreated']);
    }

    public function safeDown()
    {
        $this->dropTable('electionsVotesPops');
    }

}
