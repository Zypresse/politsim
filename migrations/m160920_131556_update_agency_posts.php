<?php

use yii\db\Migration;

class m160920_131556_update_agency_posts extends Migration
{
    public function up()
    {
        $this->dropTable('posts');
        $this->createTable('agencies-posts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL'
        ]);
        
        $this->createTable('agencies-to-posts', [
            'postId' => 'UNSIGNED INTEGER REFERENCES `agencies-posts`(id) NOT NULL',
            'agencyId' => 'UNSIGNED INTEGER REFERENCES agencies(id) NOT NULL',
        ]);
        $this->createIndex('agenciesToPosts', 'agencies-to-posts', ['postId', 'agencyId'], true);

        $this->dropTable('constitutions-posts');
        $this->createTable('constitutions-agencies-posts', [

            'postId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES `agencies-posts`(id) NOT NULL',

            // способ назначения
            // 0 - не назначается
            // 1 - назначается лидером организации
            // 2 - назначается лидером государства
            // 3 - назначается предшественником
            // 4 - выбирается на выборах по партийным спискам
            // 5 - выбирается на выборах по индивидуальным спискам
            'assignmentRule' => 'UNSIGNED INTEGER(1) NOT NULL',
            // полномочия поста (bitmask)
            // 1 право быть "диктатором", единолично принимать законы
            // 2 право вето по законопроектам
            // 4 право предлагать законопроекты
            // 8 право голосовать по законопроектам
            'powers' => 'UNSIGNED INTEGER(3) NOT NULL',

            // срок полномочий 
            'termOfOffice' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность выборов
            'termOfElections' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность регистрации на выборы
            'termOfElectionsRegistration' => 'UNSIGNED INTEGER(4) NOT NULL',

            // доп настройки выборов (bitmask)
            // 1 наличие второго тура (корректно только для выборов лидера)
            // 2 право участия в выборах беспартийных (корректно только для выборов лидера)
            'electionsRules' => 'UNSIGNED INTEGER(3) NOT NULL',
            // округ к которому привязан пост
            'electoralDistrict' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) DEFAULT NULL'
            
        ]);
        
        $this->createTable('constitutions-agencies-posts-licenses', [
            'postId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES `agencies-posts`(id) NOT NULL',
            'licenseProtoId' => 'UNSIGNED INTEGER NOT NULL',
            // права (bitmask)
            // 1 - заниматься деятельностью по лицензии
            // 2 - выдавать лицензии
            // 4 - продлять лицензии
            // 8 - отзывать лицензии
            'powers' => 'UNSIGNED INTEGER(2) NOT NULL',
        ]);
        
        $this->createTable('electoral-districts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL'
        ]);
        
        $this->createTable('electoral-districts-to-tiles', [
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
        ]);
        $this->createIndex('electoralDistrictsToTiles', 'electoral-districts-to-tiles', ['districtId', 'tileId'], true);
        
    }

    public function down()
    {
        $this->dropTable('agencies-posts');
        $this->createTable('posts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL'
        ]);
        
        $this->dropTable('agencies-to-posts');
        
        $this->dropTable('constitutions-agencies-posts');
        $this->createTable('constitutions-posts', [

            'postId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES posts(id) NOT NULL',

            // способ назначения
            // 0 - не назначается
            // 1 - назначается лидером организации
            // 2 - назначается лидером государства
            // 3 - назначается предшественником
            // 4 - выбирается на выборах по партийным спискам
            // 5 - выбирается на выборах по индивидуальным спискам
            'assignmentRule' => 'UNSIGNED INTEGER(1) NOT NULL',
            // полномочия поста (bitmask)
            // 1 право быть "диктатором", единолично принимать законы
            // 2 право вето по законопроектам
            // 4 право предлагать законопроекты
            // 8 право голосовать по законопроектам
            'powers' => 'UNSIGNED INTEGER(3) NOT NULL',

            // срок полномочий 
            'termOfOffice' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность выборов
            'termOfElections' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность регистрации на выборы
            'termOfElectionsRegistration' => 'UNSIGNED INTEGER(4) NOT NULL',

            // доп настройки выборов (bitmask)
            // 1 наличие второго тура (корректно только для выборов лидера)
            // 2 право участия в выборах беспартийных (корректно только для выборов лидера)
            'electionsRules' => 'UNSIGNED INTEGER(3) NOT NULL'
            
        ]);
        
        $this->dropTable('constitutions-agencies-posts-licenses');
        
        $this->dropTable('electoral-districts');
        $this->dropTable('electoral-districts-to-tiles');
    }
}
