<?php

use yii\db\Migration;

class m180313_183304_create_agencies_and_posts_tables extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('agencies', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'leaderId' => $this->integer()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('stateIdAgencies', 'agencies', ['stateId']);
        $this->createIndex('leaderIdAgencies', 'agencies', ['leaderId']);
        $this->createIndex('nameAgencies', 'agencies', ['name']);
        $this->createIndex('nameShortAgencies', 'agencies', ['nameShort']);
        $this->createIndex('dateCreatedAgencies', 'agencies', ['dateCreated']);
        $this->createIndex('dateDeletedAgencies', 'agencies', ['dateDeleted']);
        $this->createIndex('utrAgencies', 'agencies', ['utr'], true);
        
        $this->createTable('agenciesPosts', [
            'id' => $this->primaryKey()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'partyId' => $this->integer()->unsigned()->null(),
            'userId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('stateIdAgenciesPosts', 'agenciesPosts', ['stateId']);
        $this->createIndex('partyIdAgenciesPosts', 'agenciesPosts', ['partyId']);
        $this->createIndex('userIdAgenciesPosts', 'agenciesPosts', ['userId']);
        $this->createIndex('nameAgenciesPosts', 'agenciesPosts', ['name']);
        $this->createIndex('nameShortAgenciesPosts', 'agenciesPosts', ['nameShort']);
        $this->createIndex('utrAgenciesPosts', 'agenciesPosts', ['utr'], true);
        
        $this->createTable('agencies2posts', [
            'postId' => $this->integer()->unsigned()->notNull(),
            'agencyId' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->createIndex('agency2post', 'agencies2posts', ['postId', 'agencyId'], true);
        $this->addForeignKey('agency2postAgency', 'agencies2posts', ['agencyId'], 'agencies', ['id']);
        $this->addForeignKey('agency2postPost', 'agencies2posts', ['postId'], 'agenciesPosts', ['id']);
        
        $this->addForeignKey('leaderIdAgenciesRef', 'agencies', ['leaderId'], 'agenciesPosts', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('leaderIdAgenciesRef', 'agencies');
        
        $this->dropForeignKey('agency2postPost', 'agencies2posts');
        $this->dropForeignKey('agency2postAgency', 'agencies2posts');
        $this->dropIndex('agency2post', 'agencies2posts');
        $this->dropTable('agencies2posts');
        
        $this->dropIndex('utrAgenciesPosts', 'agenciesPosts');
        $this->dropIndex('nameShortAgenciesPosts', 'agenciesPosts');
        $this->dropIndex('nameAgenciesPosts', 'agenciesPosts');
        $this->dropIndex('userIdAgenciesPosts', 'agenciesPosts');
        $this->dropIndex('partyIdAgenciesPosts', 'agenciesPosts');
        $this->dropIndex('stateIdAgenciesPosts', 'agenciesPosts');
        $this->dropTable('agenciesPosts');
        
        $this->dropIndex('utrAgencies', 'agencies');
        $this->dropIndex('dateDeletedAgencies', 'agencies');
        $this->dropIndex('dateCreatedAgencies', 'agencies');
        $this->dropIndex('nameShortAgencies', 'agencies');
        $this->dropIndex('nameAgencies', 'agencies');
        $this->dropIndex('leaderIdAgencies', 'agencies');
        $this->dropIndex('stateIdAgencies', 'agencies');
        $this->dropTable('agencies');
    }
    
}
