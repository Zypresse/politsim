<?php

use yii\db\Migration;

class m160425_231300_addMassMediaAndEventsAndPopRequests extends Migration
{
    
    public function safeUp()
    {

        $this->createTable('events', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            'protoId' => 'INTEGER NOT NULL',
            'data' => 'TEXT DEFAULT NULL',
            'created' => 'INTEGER NOT NULL',
            'ended' => 'INTEGER NOT NULL'
        ]);

        $this->createTable('pop_requests', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            'protoId' => 'INTEGER NOT NULL',
            'eventId' => 'INTEGER DEFAULT NULL',
            'data' => 'TEXT DEFAULT NULL'
        ]);

        $this->createTable('massmedia', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            'name' => 'TEXT NOT NULL',
            'protoId' => 'INTEGER(1) NOT NULL',
            'holdingId' => 'INTEGER NOT NULL',
            'directorId' => 'INTEGER NOT NULL',
            'stateId' => 'INTEGER NOT NULL',
            'partyId' => 'INTEGER DEFAULT NULL',
            'popClassId' => 'INTEGER DEFAULT NULL',
            'popNationId' => 'INTEGER DEFAULT NULL',
            'regionId' => 'INTEGER DEFAULT NULL',
            'religionId' => 'INTEGER DEFAULT NULL',
            'ideologyId' => 'INTEGER DEFAULT NULL',
            'coverage' => 'INTEGER DEFAULT 0',
            'rating' => 'INTEGER DEFAULT 0',
            'utr' => 'INTEGER DEFAULT NULL',
            'balance' => 'REAL NOT NULL DEFAULT 0',
            'created' => 'INTEGER NOT NULL'
        ]);
        
        $this->createTable('massmedia_editors', [
            'userId' => 'INTEGER NOT NULL',
            'massmediaId' => 'INTEGER NOT NULL',
            'rules' => 'INTEGER(5) NOT NULL',
            'posts' => 'INTEGER(5) NOT NULL DEFAULT 0',
            'rating' => 'INTEGER NOT NULL DEFAULT 0',
            'hide' => 'INTEGER(1) NOT NULL DEFAULT 0',
            'customName' => 'TEXT DEFAULT NULL'
        ]);
        
        $this->createIndex('editor', 'massmedia_editors', ['userId', 'massmediaId'], true);
        
        $this->createTable('massmedia_posts', [
            'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
            'title' => 'TEXT NOT NULL',
            'text' => 'TEXT NOT NULL',
            'massmediaId' => 'INTEGER NOT NULL',
            'authorId' => 'INTEGER DEFAULT NULL',
            'eventId' => 'INTEGER DEFAULT NULL',
            'popRequestId' => 'INTEGER DEFAULT NULL',
            'votesPlus' => 'INTEGER(5) DEFAULT 0',
            'votesMinus' => 'INTEGER(5) DEFAULT 0',
            'rating' => 'INTEGER(5) DEFAULT 0',
            'created' => 'INTEGER NOT NULL'
        ]);

        $this->createTable('massmedia_posts_votes', [
            'userId' => 'INTEGER DEFAULT NULL',
            'massmediaPostId' => 'INTEGER DEFAULT NULL',
            'direction' => 'INTEGER(1) DEFAULT 0'
        ]);

        $this->createIndex('post_vote', 'massmedia_posts_votes', ['userId', 'massmediaPostId'], true);

        $this->createTable('massmedia_posts_comments', [
            'userId' => 'INTEGER DEFAULT NULL',
            'massmediaPostId' => 'INTEGER DEFAULT NULL',
            'text' => 'TEXT NOT NULL',
            'created' => 'INTEGER NOT NULL'
        ]);
    }
    
    public function safeDown()
    {
        $this->dropTable('events');
        $this->dropTable('pop_requests');
        $this->dropTable('massmedia');
        $this->dropTable('massmedia_editors');
        $this->dropTable('massmedia_posts');
        $this->dropTable('massmedia_posts_votes');
        $this->dropTable('massmedia_posts_comments');
    }

}
