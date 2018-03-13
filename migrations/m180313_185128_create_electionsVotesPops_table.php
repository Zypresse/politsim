<?php

use yii\db\Migration;

class m180313_185128_create_electionsVotesPops_table extends Migration
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
        $this->addForeignKey('electionIdVotesPopsRef', 'electionsVotesPops', ['electionId'], 'elections', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('electionIdVotesPopsRef', 'electionsVotesPops');
        $this->dropIndex('election2tile2variant', 'electionsVotesPops');
        $this->dropIndex('electionIdVotesPops', 'electionsVotesPops');
        $this->dropIndex('tileIdVotesPops', 'electionsVotesPops');
        $this->dropIndex('election2varianPops', 'electionsVotesPops');
        $this->dropIndex('districtIdVotesPops', 'electionsVotesPops');
        $this->dropIndex('dateCreatedVotesPops', 'electionsVotesPops');
        $this->dropTable('electionsVotesPops');
    }

}
