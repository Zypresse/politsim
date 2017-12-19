<?php

use yii\db\Migration;

class m170907_232600_parties extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('parties', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'stateId' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'flag' => $this->string(255)->null(),
            'anthem' => $this->string(255)->null(),
            'ideologyId' => $this->integer(3)->unsigned()->notNull(),
            'text' => $this->text()->null(),
            'textHtml' => $this->text()->null(),
            'fame' => $this->integer()->notNull()->defaultValue(0),
            'trust' => $this->integer()->notNull()->defaultValue(0),
            'success' => $this->integer()->notNull()->defaultValue(0),
            'membersCount' => $this->integer(5)->unsigned()->notNull()->defaultValue(1),
            'leaderPostId' => $this->integer()->unsigned()->notNull(),
            'joiningRules' => $this->integer(2)->unsigned()->notNull(),
            'listCreationRules' => $this->integer(2)->unsigned()->notNull(),
            'dateCreated' => $this->integer()->unsigned()->notNull(),
            'dateConfirmed' => $this->integer()->unsigned()->null(),
            'dateDeleted' => $this->integer()->unsigned()->null(),
            'utr' => $this->integer()->unsigned()->unique()->null(),
        ]);
        $this->createIndex('stateIdParties', 'parties', ['stateId']);
        $this->createIndex('nameParties', 'parties', ['name']);
        $this->createIndex('nameShortParties', 'parties', ['nameShort']);
        $this->createIndex('flagParties', 'parties', ['flag']);
        $this->createIndex('anthemParties', 'parties', ['anthem']);
        $this->createIndex('ideologyIdParties', 'parties', ['ideologyId']);
        $this->createIndex('fameParties', 'parties', ['fame']);
        $this->createIndex('trustParties', 'parties', ['trust']);
        $this->createIndex('successParties', 'parties', ['success']);
        $this->createIndex('membersCountParties', 'parties', ['membersCount']);
        $this->createIndex('dateCreatedParties', 'parties', ['dateCreated']);
        $this->createIndex('dateConfirmedParties', 'parties', ['dateConfirmed']);
        $this->createIndex('dateDeletedParties', 'parties', ['dateDeleted']);
        $this->createIndex('utrParties', 'parties', ['utr'], true);
        
        $this->createTable('partiesPosts', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'partyId' => $this->integer()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->null(),
            'name' => $this->string(255)->notNull(),
            'nameShort' => $this->string(10)->notNull(),
            'powers' => $this->integer(4)->unsigned()->notNull(),
            'appointmentType' => $this->integer(2)->unsigned()->notNull(),
            'successorId' => $this->integer()->unsigned()->null(),
        ]);
        $this->createIndex('partyIdPosts', 'partiesPosts', ['partyId']);
        $this->createIndex('userIdPosts', 'partiesPosts', ['userId']);
        $this->createIndex('successorId', 'partiesPosts', ['successorId']);
        
        $this->addForeignKey('stateIdPartiesRef', 'parties', ['stateId'], 'states', ['id']);
        $this->addForeignKey('leaderPostIdPartiesRef', 'parties', ['leaderPostId'], 'partiesPosts', ['id']);
        $this->addForeignKey('partyIdPostsRef', 'partiesPosts', ['partyId'], 'parties', ['id']);
        $this->addForeignKey('successorIdPostsRef', 'partiesPosts', ['successorId'], 'partiesPosts', ['id']);
        
        $this->addForeignKey('partyIdAgencyPostsRef', 'agenciesPosts', ['partyId'], 'parties', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('partyIdAgencyPostsRef', 'agenciesPosts');
        
        $this->dropForeignKey('stateIdPartiesRef', 'parties');
        $this->dropForeignKey('leaderPostIdPartiesRef', 'parties');
        $this->dropForeignKey('partyIdPostsRef', 'partiesPosts');
        $this->dropForeignKey('successorIdPostsRef', 'partiesPosts');
        
        $this->dropIndex('partyIdPosts', 'partiesPosts', ['partyId']);
        $this->dropIndex('userIdPosts', 'partiesPosts', ['userId']);
        $this->dropIndex('successorId', 'partiesPosts', ['successorId']);
        $this->dropTable('partiesPosts');
        
        $this->dropIndex('stateIdParties', 'parties', ['stateId']);
        $this->dropIndex('nameParties', 'parties', ['name']);
        $this->dropIndex('nameShortParties', 'parties', ['nameShort']);
        $this->dropIndex('flagParties', 'parties', ['flag']);
        $this->dropIndex('anthemParties', 'parties', ['anthem']);
        $this->dropIndex('ideologyIdParties', 'parties', ['ideologyId']);
        $this->dropIndex('fameParties', 'parties', ['fame']);
        $this->dropIndex('trustParties', 'parties', ['trust']);
        $this->dropIndex('successParties', 'parties', ['success']);
        $this->dropIndex('membersCountParties', 'parties', ['membersCount']);
        $this->dropIndex('dateCreatedParties', 'parties', ['dateCreated']);
        $this->dropIndex('dateConfirmedParties', 'parties', ['dateConfirmed']);
        $this->dropIndex('dateDeletedParties', 'parties', ['dateDeleted']);
        $this->dropIndex('utrParties', 'parties', ['utr'], true);
        $this->dropTable('parties');
    }

}
