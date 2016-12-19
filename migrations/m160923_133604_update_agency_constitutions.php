<?php

use yii\db\Migration;

class m160923_133604_update_agency_constitutions extends Migration
{
    public function up()
    {

        $this->dropTable('constitutionsAgencies');
        
        $this->createTable('constitutionsAgencies', [ // куски конституции для агенств
            
            'agencyId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES agencies(id) NOT NULL',

            // пост формального лидера агенства
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            // способ назначения членов организации
            // 0 - не назначаются
            // 1 - назначаются лидером организации
            // 2 - назначаются лидером государства
            // 3 - назначаются предшественником
            // 4 - выбираются на выборах по партийным спискам
            // 5 - выбираются на выборах по индивидуальным спискам
            'assignmentRule' => 'UNSIGNED INTEGER(1) NOT NULL',
            // полномочия постов (bitmask)
            // 1 право быть "диктатором", единолично принимать законы
            // 2 право вето по законопроектам
            // 4 право предлагать законопроекты
            // 8 право голосовать по законопроектам
            'powers' => 'UNSIGNED INTEGER(4) NOT NULL',

            // срок полномочий 
            'termOfOffice' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность выборов
            'termOfElections' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность регистрации на выборы
            'termOfElectionsRegistration' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            // число одинаковых постов типа парламентариев
            'tempPostsCount' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            //  базовый пост для шаблонных
            'tempPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            
        ]);
    }

    public function down()
    {
        
        $this->dropTable('constitutionsAgencies');
        
        $this->createTable('constitutionsAgencies', [ // куски конституции для агенств
            
            'agencyId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES agencies(id) NOT NULL',

            // пост формального лидера агенства
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            // способ назначения членов организации
            // 0 - не назначаются
            // 1 - назначаются лидером организации
            // 2 - назначаются лидером государства
            // 3 - назначаются предшественником
            // 4 - выбираются на выборах по партийным спискам
            // 5 - выбираются на выборах по индивидуальным спискам
            'assignmentRule' => 'UNSIGNED INTEGER(1) NOT NULL',
            // полномочия постов (bitmask)
            // 1 право быть "диктатором", единолично принимать законы
            // 2 право вето по законопроектам
            // 4 право предлагать законопроекты
            // 8 право голосовать по законопроектам
            'powers' => 'UNSIGNED INTEGER(4) NOT NULL',

            // срок полномочий 
            'termOfOffice' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность выборов
            'termOfElections' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность регистрации на выборы
            'termOfElectionsRegistration' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            // число одинаковых постов типа парламентариев
            'tempPostsCount' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            
        ]);
    }

}
