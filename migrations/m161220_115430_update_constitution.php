<?php

use yii\db\Migration;

class m161220_115430_update_constitution extends Migration
{
    public function up()
    {
        $this->dropTable('constitutions');
        $this->dropTable('constitutionsLicenses');
        $this->dropTable('constitutionsAgencies');
        $this->dropTable('constitutionsAgenciesLicenses');
        $this->dropTable('constitutionsAgenciesPosts');
        $this->dropTable('constitutionsAgenciesPostsLicenses');
        $this->dropTable('constitutionsRegions');
        
        $this->createTable('constitutionsArticles', [
            'ownerType' => 'UNSIGNED INTEGER(1) NOT NULL',
            'ownerId' => 'UNSIGNED INTEGER NOT NULL',
            'type' => 'UNSIGNED INTEGER(5) NOT NULL',
            'subType' => 'UNSIGNED INTEGER(5) DEFAULT NULL',
            'value' => 'TEXT NOT NULL',
            'value2' => 'TEXT DEFAULT NULL',
            'value3' => 'TEXT DEFAULT NULL',
        ]);
        $this->createIndex('constitutionArticlesPrimary', 'constitutionsArticles', ['ownerType', 'ownerId', 'type', 'subType'], true);
        
    }

    public function down()
    {
        
        $this->dropTable('constitutionsArticles');
        
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
        
        $this->createTable('constitutionsAgenciesPosts', [

            'postId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES `agenciesPosts`(id) NOT NULL',

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
            'electoralDistrict' => 'UNSIGNED INTEGER REFERENCES `electoralDistricts`(id) DEFAULT NULL'
            
        ]);
        
        $this->createTable('constitutionsAgenciesPostsLicenses', [
            'postId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES `agenciesPosts`(id) NOT NULL',
            'licenseProtoId' => 'UNSIGNED INTEGER NOT NULL',
            // права (bitmask)
            // 1 - заниматься деятельностью по лицензии
            // 2 - выдавать лицензии
            // 4 - продлять лицензии
            // 8 - отзывать лицензии
            'powers' => 'UNSIGNED INTEGER(2) NOT NULL',
        ]);
        
        $this->createTable('constitutions', [ // "Конституции" стран
            'stateId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES states(id) NOT NULL',
            
            // партийная политика. 
            // 0 - запрещена деятельность партий
            // 1 - разрешена деятельность правящей партии, остальные запрещены
            // 2 - разрешена деятельность уже зарегистрированных партий, регистрация новых запрещена
            // 3 - -//- регистрация новых требует подтверждения соотв. министра
            // 4 - -//- регистрация свободная
            'partyPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // правящая партия
            'rulingPartyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            // пошлина за регистрацию партии
            'partyRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',

            // разрешение занимать несколько постов одному юзеру
            'isAllowMultipost' => 'BOOLEAN NOT NULL',
            
            // пост формального лидера государства
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            // компания выполняющая роль центробанка
            'centralBankId' => 'UNSIGNED INTEGER REFERENCES companies(id) DEFAULT NULL',
            // официальная валюта
            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) DEFAULT NULL',
            // разрешение центробанку устанавливать курс валюты вручную
            'isAllowSetExchangeRateManually' => 'BOOLEAN NOT NULL DEFAULT 0',
            // налог на доход
            'taxBase' => 'UNSIGNED REAL NOT NULL DEFAULT 0.1',

            // права на ведение бизнеса
            // 0 - запрещено всем
            // 1 - разрешено владение, запрещена регистрация всем
            // 2 - разрешено владение и регистрация всем
            // 3 - запрещено иностранцам, своим разрешено владение, запрещена регистрация
            // 4 - запрещено иностранцам, своим разрешено владение и регистрация
            // 5 - запрещена регистрация иностранцам, но разрешено владение, своим запрещена регистрация, разрешено владение
            // 6 - запрещена регистрация иностранцам, но разрешено владение, своим разрешено владение и регистрация
            'businessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
                        
            // минимальная зарплата
            'minWage' => 'UNSIGNED REAL DEFAULT NULL',
            // пенсионный возраст
            'retirementAge' => 'UNSIGNED INTEGER(3) DEFAULT 65',
            
            // государственная религия
            'religionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            
            // способ назначения лидеров регионов
            'regionsLeadersAssignmentRule' => 'UNSIGNED INTEGER(1) NOT NULL',
            
        ]); 
        
        $this->createTable('constitutionsLicenses', [
            'stateId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES states(id) NOT NULL',
            'licenseProtoId' => 'UNSIGNED INTEGER NOT NULL',
            // права на ведение бизнеса
            // 0 - запрещено всем
            // 1 - разрешено владение, запрещена регистрация всем
            // 2 - разрешено владение и регистрация всем
            // 3 - запрещено иностранцам, своим разрешено владение, запрещена регистрация
            // 4 - запрещено иностранцам, своим разрешено владение и регистрация
            // 5 - запрещена регистрация иностранцам, но разрешено владение, своим запрещена регистрация, разрешено владение
            // 6 - запрещена регистрация иностранцам, но разрешено владение, своим разрешено владение и регистрация
            'policy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            'localRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            'foreignRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            'localMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            'foreignMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
        ]);
        $this->createIndex('stateToLicense', 'constitutionsLicenses', ['stateId', 'licenseProtoId'], true);
        
        $this->createTable('constitutionsAgenciesLicenses', [
            'agencyId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES agencies(id) NOT NULL',
            'licenseProtoId' => 'UNSIGNED INTEGER NOT NULL',
            // права (bitmask)
            // 1 - заниматься деятельностью по лицензии
            // 2 - выдавать лицензии
            // 4 - продлять лицензии
            // 8 - отзывать лицензии
            'powers' => 'UNSIGNED INTEGER(2) NOT NULL',
        ]);
                
        $this->createTable('constitutionsRegions', [
            'regionId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES regions(id) NOT NULL',

            // пост формального лидера региона
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            
        ]);
    }
}
