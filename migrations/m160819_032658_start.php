<?php

use yii\db\Migration;

class m160819_032658_start extends Migration
{
    public function safeUp()
    {
        $this->createConstitutionsTables();

        $this->createAccountsTable();

        $this->createBillsTables();

        $this->createHistoricalCountriesTables();

        $this->createDealingsTables();

        $this->createElectionsTables();

        $this->createEventsTables();

        $this->createBuildingsTables();

        $this->createCompaniesTables();

        $this->createInvitesTable();
        
        $this->createLicensesTables();

        $this->createMassmediaTables();

        $this->createModifiersTable();

        $this->createNotificationsTable();

        $this->createAgenciesTable();

        $this->createPartiesTables();

        $this->createPostsTable();

        $this->createPopsTable();

        $this->createMapTables();

        $this->createResourcesTables();

        $this->createTaxesTables();

        $this->createTwitterTables();

        $this->createUsersTables();

        $this->createMilitaryTables();

        $this->createUTRTable();
        
    }
    
    private function createConstitutionsTables()
    {
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
            'rulingParty' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
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

            // права местной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'localBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для местной элиты
            'localBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для местной элиты 
            'localBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для местной элиты 
            'localBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
                        
            // права иностранной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'foreignBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для иностранной элиты
            'foreignBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для иностранной элиты 
            'foreignBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для иностранной элиты 
            'foreignBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
            
            // права местной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'npcBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для нпц
            'npcBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для нпц
            'npcBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для нпц
            'npcBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
            
            // минимальная зарплата
            'minWage' => 'UNSIGNED REAL DEFAULT NULL',
            // пенсионный возраст
            'retirementAge' => 'UNSIGNED INTEGER(3) DEFAULT 65',
            
            // государственная религия
            'stateReligion' => 'UNSIGNED INTEGER(3) DEFAULT NULL'
            
        ]); 
        
        $this->createTable('constitutions-agencies', [ // куски конституции для агенств
            
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
            'powers' => 'UNSIGNED INTEGER(3) NOT NULL',

            // срок полномочий 
            'termOfOffice' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность выборов
            'termOfElections' => 'UNSIGNED INTEGER(4) NOT NULL',
            // длительность регистрации на выборы
            'termOfElectionsRegistration' => 'UNSIGNED INTEGER(4) NOT NULL',

            // права на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'agencyBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL'

        ]);
        
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
            'electionsRules' => 'UNSIGNED INTEGER(3) NOT NULL',

            // права на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'agencyBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        
        $this->createTable('constitutions-regions', [

            'regionId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES regions(id) NOT NULL',

            // пост формального лидера региона
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            ///// экономические \\\\\
            
            // права местной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'localBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для местной элиты
            'localBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для местной элиты 
            'localBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для местной элиты 
            'localBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
                        
            // права иностранной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'foreignBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для иностранной элиты
            'foreignBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для иностранной элиты 
            'foreignBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для иностранной элиты 
            'foreignBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
            
            // права местной элиты на ведение бизнеса
            // 0 - запрещено
            // 1 - разрешено владение, запрещена регистрация
            // 2 - разрешено владение и регистрация
            'npcBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL', 
            // пошлина за регистрацию компании для нпц
            'npcBusinessRegistrationTax' => 'UNSIGNED REAL DEFAULT NULL',
            // минимальная стартовая капитализация компаний для нпц
            'npcBusinessMinCapital' => 'UNSIGNED REAL DEFAULT NULL',
            // максимальная стартовая капитализация компаний для нпц
            'npcBusinessMaxCapital' => 'UNSIGNED REAL DEFAULT NULL',
            
            // минимальная зарплата
            'minWage' => 'UNSIGNED REAL DEFAULT NULL',
            // пенсионный возраст
            'retirementAge' => 'UNSIGNED INTEGER(3) DEFAULT 65'
        ]);
    }

    private function createAccountsTable()
    {
        $this->createTable('accounts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'source' => 'UNSIGNED INTEGER(1) NOT NULL',
            'sourceId' => 'VARCHAR(255) NOT NULL'
        ]);
        $this->createIndex('accountsSources', 'accounts', ['source', 'sourceId'], true);
    }

    private function createBillsTables()
    {
        $this->createTable('bills', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            
            // двойная привязка к юзеру и посту для того чтобы корректно отображалось после того как юзер перейдёт на другой пост
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL',

            'vetoPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            'isDictatorBill' => 'BOOLEAN NOT NULL DEFAULT 0',

            'votesPlus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',

            'data' => 'TEXT DEFAULT NULL'
        ]);
        

        $this->createTable('bills-votes', [
            'billId' => 'UNSIGNED INTEGER REFERENCES bills(id) NOT NULL',
            'postId' => 'UNSIGNED INTEGER REFERENCES posts(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        $this->createIndex('billVotePost', 'bills-votes', ['billId', 'postId'], true);
    }

    private function createHistoricalCountriesTables()
    {
        $this->createTable('historical-countries', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'tilesCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0'
        ]);

        $this->createTable('historical-countries-to-tiles', [
            'historicalCountryId' => 'UNSIGNED INTEGER REFERENCES `historical-countries`(id) NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL'
        ]);
        $this->createIndex('historicalCountriesToTiles', 'historical-countries-to-tiles', ['historicalCountryId', 'tileId'], true);

        $this->createTable('historical-countries-claims', [
            'historicalCountryId' => 'UNSIGNED INTEGER REFERENCES `historical-countries`(id) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'controlledTiles' => 'UNSIGNED INTEGER(5) NOT NULL'
        ]);
        $this->createIndex('historicalCountriesClaims', 'historical-countries-claims', ['historicalCountryId', 'stateId'], true);
    }

    private function createDealingsTables()
    {
        $this->createTable('dealings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiator' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'receiver' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->createTable('dealings-items', [
            'dealingId' => 'UNSIGNED INTEGER REFERENCES dealings(id) NOT NULL',
            
            // 0 - от иницаиатора к ресиверу, 1 - от ресивера к инициатору
            'direction' => 'BOOLEAN NOT NULL',

            // тип передаваемых вещей
            // 1. деньги (id валюты)
            // 2. акции компании (id компании)
            // 3. ресурсы (id прототипа ресурса)
            // 4. здания (id здания)
            // 5. обьекты инфраструктуры (id объекта)
            'type' => 'UNSIGNED INTEGER(2) NOT NULL',
            'protoId' => 'UNSIGNED INTEGER NOT NULL',
            'subProtoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            'quality' => 'UNSIGNED INTEGER DEFAULT NULL',
            'deterioration' => 'UNSIGNED INTEGER DEFAULT NULL',
            'locationId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',

            'count' => 'UNSIGNED REAL NOT NULL'
        ]);
    }

    private function createElectionsTables()
    {
        // новый обьект выборов должен автоматически создаваться/удаляться при изменениях конституции или завершении предыдущего голосования
        $this->createTable('elections', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            // 1 - в агенство
            // 2 - на пост
            // так же можно будет сделать референдумы напр.
            'protoId' => 'UNSIGNED INTEGER(1) NOT NULL',
            'agencyId'  => 'UNSIGNED INTEGER REFERENCES agencies(id) DEFAULT NULL',            
            'postId'  => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            'isIndividual' => 'BOOLEAN NOT NULL',
            'isOnlyParty' => 'BOOLEAN NOT NULL',
            'dateRegistrationStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingEnd' => 'UNSIGNED INTEGER NOT NULL',
            'results' => 'TEXT DEFAULT NULL'
        ]);

        $this->createTable('elections-requests-party', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',

            'partyListId' => 'UNSIGNED INTEGER REFERENCES `parties-lists`(id) NOT NULL',
            // порядковый номер заявки
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsRequestsParty', 'elections-requests-party', ['electionId', 'partyId'], true);
        $this->createIndex('electionsRequestsPartyVariant', 'elections-requests-party', ['electionId', 'variant'], true);

        $this->createTable('elections-requests-individual', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',

            // порядковый номер заявки
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsRequestsIndividual', 'elections-requests-individual', ['electionId', 'userId'], true);
        $this->createIndex('electionsRequestsIndividualVariant', 'elections-requests-individual', ['electionId', 'variant'], true);

        $this->createTable('elections-votes-users', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsers', 'elections-votes-users', ['electionId', 'userId'], true);

        $this->createTable('elections-votes-pops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);

    }

    private function createEventsTables()
    {
        $this->createTable('events', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'initiatorId' => 'UNSIGNED INTEGER DEFAULT NULL',
            'subjectId' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateEnded' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->createTable('pops-requests', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'data' => 'TEXT DEFAULT NULL',
            'power' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateEnded' => 'UNSIGNED INTEGER DEFAULT NULL',
            'isApproved' => 'BOOLEAN NOT NULL DEFAULT 0',
        ]);

        $this->createTable('pops-requests-power', [
            'requestId' => 'UNSIGNED INTEGER REFERENCES `pops-requests`(id) NOT NULL',
            'popId' => 'UNSIGNED INTEGER REFERENCES pops(id) NOT NULL',
            'powerAbsolute' => 'UNSIGNED INTEGER NOT NULL',
            'powerRelative' => 'UNSIGNED REAL NOT NULL'
        ]);
        $this->createIndex('popsRequestsPower', 'pops-requests-power', ['requestId', 'popId'], true);

    }

    private function createBuildingsTables()
    {

        $this->createTable('buildings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'size' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            // Износ от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyBase' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // базовая эффективность (напр. от региона)

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (номер в списке возможных заданий прототипа здания)
            'task' => 'UNSIGNED INTEGER(2) DEFAULT NULL',
            'taskSubProtoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поля для регулировки баланса количество/качество. В сумме должны давать 1.
            'taskCount' => 'UNSIGNED REAL DEFAULT 0.5',
            'taskQuality' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
    
        $this->createTable('buildings-twotiled', [            
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'tile2Id' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'size' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            // Износ от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyBase' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // базовая эффективность (напр. от региона)

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (номер в списке возможных заданий прототипа здания)
            'task' => 'UNSIGNED INTEGER(2) DEFAULT NULL',
            'taskSubProtoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поля для регулировки баланса количество/качество. В сумме должны давать 1.
            'taskCount' => 'UNSIGNED REAL DEFAULT 0.5',
            'taskQuality' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->createTable('units', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'tileWhereMovingId' => 'UNSIGNED INTEGER REFERENCES tiles(id) DEFAULT NULL',
            'tileWhereMovingCurrentId' => 'UNSIGNED INTEGER REFERENCES tiles(id) DEFAULT NULL',

            'dateCurrentMovingStart' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateCurrentMovingEnd' => 'UNSIGNED INTEGER DEFAULT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'size' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            // Износ от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyManagement' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // управление
            'efficiencyBase' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // базовая эффективность (напр. от региона)

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (номер в списке возможных заданий прототипа здания)
            'task' => 'UNSIGNED INTEGER(2) DEFAULT NULL',
            'taskSubProtoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поля для регулировки баланса количество/качество. В сумме должны давать 1.
            'taskCount' => 'UNSIGNED REAL DEFAULT 0.5',
            'taskQuality' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);        

        $this->createTable('auctions-unmovable-objects', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',

            // валюта
            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'priceStart' => 'UNSIGNED REAL NOT NULL',
            'priceCurrent' => 'UNSIGNED REAL DEFAULT NULL',
            'priceStop' => 'UNSIGNED REAL DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateEnded' => 'UNSIGNED INTEGER NOT NULL',

            'winner' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->createTable('auctions-unmovable-objects-bets', [
            'auctionId' => 'UNSIGNED INTEGER REFERENCES `auctions-unmovable-objects`(id) NOT NULL',
            'betterId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'value' => 'UNSIGNED REAL NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER'
        ]);
        $this->createIndex('auctionsUnmovableObjectsBets', 'auctions-unmovable-objects-bets', ['auctionId', 'betterId'], true);

        $this->createTable('unmovable-objects-settings-autoselling', [
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'resourceProtoId' => 'INTEGER NOT NULL', // услуги имеют отрицательные ID прототипа
            
            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'price' => 'UNSIGNED REAL NOT NULL' // цена за единицу
        ]);
        $this->createIndex('unmovableObjectsSettingsAutoselling', 'unmovable-objects-settings-autoselling', ['objectId', 'resourceProtoId'], true);

        $this->createTable('unmovable-objects-settings-autopurchase', [            
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'resourceProtoId' => 'INTEGER NOT NULL', // услуги имеют отрицательные ID прототипа

            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'count' => 'UNSIGNED REAL NOT NULL', // количество закупаемого в час
            'maxPrice' => 'UNSIGNED REAL NOT NULL', // максимальная цена за единицу
            'minQuality' => 'UNSIGNED INTEGER NOT NULL' // минимальное качество
        ]);
        $this->createIndex('unmovableObjectsSettingsAutopurchase', 'unmovable-objects-settings-autopurchase', ['objectId', 'resourceProtoId'], true);

        $this->createTable('pops-vacancies', [
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'popClassId' => 'UNSIGNED INTEGER(2) NOT NULL',

            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'wage' => 'UNSIGNED REAL NOT NULL',

            'countAll' => 'UNSIGNED INTEGER NOT NULL',
            'countFree' => 'UNSIGNED INTEGER NOT NULL',

            'popNationId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'popNationGroupId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popReligionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popGenderId' => 'UNSIGNED INTEGER(1) DEFAULT NULL',
            'popAgeMin' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popAgeMax' => 'UNSIGNED INTEGER(3) DEFAULT NULL'
        ]);
        $this->createIndex('popsVacancies', 'pops-vacancies', ['objectId', 'popClassId'], true);

    }

    private function createCompaniesTables()
    {

        $this->createTable('companies', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'mainOfficeId' => 'UNSIGNED INTEGER REFERENCES buildings(id) DEFAULT NULL',
            'directorId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'efficiencyManagement' => 'UNSIGNED REAL NOT NULL DEFAULT 1',
            'sharesIssued' => 'UNSIGNED INTEGER NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->createTable('companies-decisions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'companyId' => 'UNSIGNED INTEGER REFERENCES companies(id) NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiatorId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'data' => 'TEXT DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingEnd' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL',
            'percentAgreement' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'percentDisagreement' => 'UNSIGNED REAL NOT NULL DEFAULT 0'
        ]);

        $this->createTable('companies-decisions-votes', [
            'decisionId' => 'UNSIGNED INTEGER REFERENCES `companies-decisions`(id) NOT NULL',
            'shareholderId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(1) NOT NULL' // 0 - воздержался, 1 - за, 2 - против или номера вариантов решения
        ]);
        $this->createIndex('companiesDecisionsVotes', 'companies-decisions-votes', ['decisionId', 'shareholderId'], true);

    }

    private function createInvitesTable()
    {
        $this->createTable('invites', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'code' => 'VARCHAR(255) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'dateActivated' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('invitesCodes', 'invites', ['code'], true);
        $this->createIndex('invitesUsers', 'invites', ['userId'], true);
    }

    private function createLicensesTables()
    {
        $this->createTable('licenses', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'INTEGER NOT NULL', // не unsigned потому что у услуг отрицательные айдишники
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'companyId' => 'UNSIGNED INTEGER REFERENCES companies(id) NOT NULL',
            'datePending' => 'UNSIGNED INTEGER NOT NULL', // подача заявки
            'dateGranted' => 'UNSIGNED INTEGER DEFAULT NULL', // лицензия выдана
            'dateExpired' => 'UNSIGNED INTEGER DEFAULT NULL' // срок действия лицензии истёкает
        ]);

        $this->createTable('licenses-rules', [
            'protoId' => 'INTEGER NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            // bitmask
            // 1 - госкомпании (> 50% государства)
            // 2 - компании с гос. участием
            // 4 - компании-резиденты
            // 8 - компании-нерезиденты
            'whichCompaniesAllowed' => 'UNSIGNED INTEGER(2) NOT NULL',
            'isNeedConfirmation' => 'BOOLEAN NOT NULL DEFAULT 0',
            'priceForResidents' => 'UNSIGNED REAL DEFAULT NULL',
            'priceForNonresidents' => 'UNSIGNED REAL DEFAULT NULL'
        ]);
        $this->createIndex('licensesRules', 'licenses-rules', ['protoId', 'stateId'], true);

    }

    public function createMassmediaTables()
    {
        $this->createTable('massmedias', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'companyId' => 'UNSIGNED INTEGER REFERENCES massmedias(id) NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'popNationId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'popNationGroupId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popReligionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popIdeologyId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popGenderId' => 'UNSIGNED INTEGER(1) DEFAULT NULL',
            'popAgeMin' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'popAgeMax' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'audienceCoverage' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'elitesCoverage' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'elitesRating' => 'INTEGER NOT NULL DEFAULT 0',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateLastPost' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->createTable('massmedias-subscribes', [
            'massmediaId' => 'UNSIGNED INTEGER REFERENCES massmedias(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'dateSubcribed' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('massmediasSubscribes', 'massmedias-subscribes', ['massmediaId', 'userId'], true);

        $this->createTable('massmedias-posts', [
            'id' =>'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'massmediaId' => 'UNSIGNED INTEGER REFERENCES massmedias(id) NOT NULL',
            'authorId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',

            'isAnonimous' => 'BOOLEAN NOT NULL DEFAULT 0',

            'title' => 'VARCHAR(255) NOT NULL',
            'value' => 'TEXT DEFAULT NULL',

            'popRequestId' => 'UNSIGNED INTEGER REFERENCES `pops-requests`(id) DEFAULT NULL',

            'votesPlus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesMinus' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'votesAbstain' => 'UNSIGNED INTEGER(5) NOT NULL DEFAULT 0',
            'rating' => 'INTEGER NOT NULL DEFAULT 0',

            'audienceCoverage' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateEdited' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->createTable('massmedias-posts-votes', [
            'postId' => 'UNSIGNED INTEGER REFERENCES `massmedias-posts`(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        $this->createIndex('massmediasPostsVotes', 'massmedias-posts-votes', ['postId', 'userId'], true);

        $this->createTable('massmedias-posts-comments', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'postId' => 'UNSIGNED INTEGER REFERENCES `massmedias-posts`(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'text' => 'TEXT NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateEdited' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
    }

    private function createModifiersTable()
    {
        $this->createTable('modifiers', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'protoId' => 'UNSIGNED INTEGER(5) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'dateReceiving' => 'UNSIGNED INTEGER NOT NULL',
            'dateExpired' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
    }

    public function createNotificationsTable()
    {
        $this->createTable('notifications', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'protoId' => 'UNSIGNED INTEGER(5) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'text' => 'TEXT NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }

    public function createAgenciesTable()
    {
        $this->createTable('agencies', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }

    public function createPartiesTables()
    {
        $this->createTable('parties', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'ideology' => 'UNSIGNED INTEGER(3) NOT NULL',
            'text' => 'TEXT NOT NULL',
            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'membersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 1',
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES `parties-posts`(id) NOT NULL',
            // 0 закрытая
            // 1 по заявкам
            // 2 свободно
            'joiningRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            // способ формирования партийного списка
            // 1 лидером
            // 2 праймериз
            'listCreationRules' => 'UNSIGNED INTEGER(1) NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);

        $this->createTable('parties-posts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            // bitmask
            // 1 изменение основных полей 
            // 2 создание и редактирование постов
            // 4 принятие заявок в партию
            'powers' => 'UNSIGNED INTEGER(3) NOT NULL',
            // 1 назнач лидером
            // 2 назначается предыдущим владельцем
            // 3 выбирается на праймериз
            'appointmentType' => 'UNSIGNED INTEGER(1) NOT NULL'
        ]);
        $this->createIndex('partiesPosts', 'parties-posts', ['partyId', 'userId'], true);

        $this->createTable('parties-lists', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',
            'isPrimaries' => 'BOOLEAN NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'datePrimariesEnds' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateEdited' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
        
        $this->createTable('parties-lists-members', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'listId' => 'UNSIGNED INTEGER REFERENCES `parties-list`(id) NOT NULL',
            'position' => 'UNSIGNED INTEGER NOT NULL',
            'votes' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('partiesListsMembersUser', 'parties-lists-members', ['userId', 'listId'], true);
        $this->createIndex('partiesListsMembersPosition', 'parties-lists-members', ['position', 'listId'], true);
        
        $this->createTable('parties-lists-members-votes', [
            'memberId' => 'UNSIGNED INTEGER REFERENCES `parties-lists-members`(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL'
        ]);
        $this->createIndex('partiesListsMembersVotes', 'parties-lists-members-votes', ['userId', 'memberId'], true);
        
    }

    private function createPostsTable()
    {
        $this->createTable('posts', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) DEFAULT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL'
        ]);
    }

    private function createPopsTable()
    {
        $this->createTable('pops', [
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'workplaceId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'livingplaceId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'classId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'nationId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'religionId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'genderId' => 'UNSIGNED INTEGER(1) NOT NULL',
            'age' => 'UNSIGNED INTEGER(3) NOT NULL',
            'contentment' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'consciousness' => 'UNSIGNED REAL NOT NULL DEFAULT 0',
            'dateLastWageGet' => 'UNSIGNED INTEGER DEFAULT NULL',
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('popsUnique', 'pops', ['tileId', 'workplaceId', 'livingplaceId', 'classId', 'nationId', 'ideologyId', 'religionId', 'genderId', 'age'], true);
    }

    private function createMapTables()
    {
        $this->createTable('regions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->createTable('cities', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'flag' => 'TEXT NOT NULL',
            'athem' => 'TEXT NOT NULL',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'usersFame' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'            
        ]);

        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'lat' => 'REAL NOT NULL',
            'lon' => 'REAL NOT NULL',
            'isWater' => 'BOOLEAN NOT NULL DEFAULT 1',
            'isLand' => 'BOOLEAN NOT NULL DEFAULT 0',
            'isMountains' => 'BOOLEAN NOT NULL DEFAULT 0',
            'population' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'regionId' => 'UNSIGNED INTEGER REFERENCES regions(id) DEFAULT NULL',
            'cityId' => 'UNSIGNED INTEGER REFERENCES cities(id) DEFAULT NULL'
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x', 'y'], true);

        $this->createTable('tiles-resources', [
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'resourceProtoId' => 'UNSIGNED INTEGER NOT NULL',
            'miningFactor' => 'UNSIGNED REAL NOT NULL DEFAULT 1'
        ]);
        $this->createIndex('tilesResources', 'tiles-resources', ['tileId', 'resourceProtoId'], true);

    }

    private function createResourcesTables()
    {
        $this->createTable('resources', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',            
            'protoId' => 'INTEGER NOT NULL',
            'subProtoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'locationId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'quality' => 'UNSIGNED INTEGER(3) DEFAULY NULL',
            'deterioration' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'count' => 'UNSIGNED REAL NOT NULL DEFAULT 0'
        ]);
        $this->createIndex('resourcesUnique', 'resources', ['protoId', 'subProtoId', 'masterId', 'locationId', 'quality', 'deterioration'], true);

        $this->createTable('currencies', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'emissionerId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'inflation' => 'REAL NOT NULL DEFAULT 0',
            'exchangeRate' => 'REAL DEFAULT NULL',
            'exchangeRateReal' => 'REAL NOT NULL DEFAULT 1'
        ]);

        $this->createTable('resources-prices', [
            'resourceId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES resources(id) NOT NULL',
            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'price' => 'UNSIGNED REAL NOT NULL' // за единицу
        ]);
    }

    private function createTaxesTables()
    {
        $this->createTable('taxes-resources-selling', [
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'protoId' => 'INTEGER NOT NULL',
            'value' => 'UNSIGNED REAL NOT NULL'
        ]);
        $this->createIndex('taxesResourcesSellingUnique', 'taxes-resources-selling', ['stateId', 'protoId'], true);

        $this->createTable('taxes-buildings-holding', [
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'protoId' => 'INTEGER NOT NULL',
            'value' => 'UNSIGNED REAL NOT NULL'
        ]);
        $this->createIndex('taxesBuildingsHoldingUnique', 'taxes-buildings-holding', ['stateId', 'protoId'], true);
    }

    private function createTwitterTables()
    {
        $this->createTable('tweets', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'text' => 'VARCHAR(255) DEFAULT NULL',
            'audienceCoverage' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'retweetsCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'originalId' => 'UNSIGNED INTEGER REFERENCES tweets(id) DEFAULT NULL',
            'isDeleted' => 'BOOLEAN NOT NULL DEFAULT 0',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);

        $this->createTable('twitter-profiles', [
            'userId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES users(id) NOT NULL',
            'nickname' => 'VARCHAR(255) NOT NULL',
            'followersCount' => 'UNSIGNED INTEGER NOT NULL DEFAULT 0',
            'dateLastTweet' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('twitterProfilesNickname', 'twitter-profiles', ['nickname'], true);

        $this->createTable('twitter-subscribes', [
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'followerId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'dateSubcribed' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('twitterSubscribes', 'twitter-subscribes', ['userId', 'followerId'], true);
    }

    private function createUsersTables()
    {
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'avatar' => 'TEXT NOT NULL',
            'avatarBig' => 'TEXT NOT NULL',
            'genderId' => 'UNSIGNED INTEGER(1) NOT NULL',

            'locationId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',

            'ideologyId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',
            'religionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL',

            'fame' => 'INTEGER NOT NULL DEFAULT 0',
            'trust' => 'INTEGER NOT NULL DEFAULT 0',
            'success' => 'INTEGER NOT NULL DEFAULT 0',
            'fameBase' => 'INTEGER NOT NULL DEFAULT 0',
            'trustBase' => 'INTEGER NOT NULL DEFAULT 0',
            'successBase' => 'INTEGER NOT NULL DEFAULT 0',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateLastLogin' => 'UNSIGNED INTEGER NOT NULL',

            'isInvited' => 'BOOLEAN NOT NULL DEFAULT 0',
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);

        $this->createTable('users-to-parties', [
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('usersToPartiesUnique', 'users-to-parties', ['userId', 'partyId'], true);

        $this->createTable('citizenships', [
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'stateId' => 'UNSIGNED INTEGER REFERENCES states(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('citizenshipsUnique', 'citizenships', ['userId', 'stateId'], true);
    }

    private function createMilitaryTables()
    {
        $this->createTable('military-units', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',

            'unitId' => 'UNSIGNED INTEGER REFERENCES units(id) NOT NULL',
            'commanderId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',

            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
    }
    
    private function createUTRTable()
    {
        $this->createTable('utr', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'objectId' => 'UNSIGNED INTEGER NOT NULL',
            'objectType' => 'UNSIGNED INTEGER(2) NOT NULL'
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('constitutions');
        $this->dropTable('constitutions-agencies');
        $this->dropTable('constitutions-posts');
        $this->dropTable('constitutions-regions');

        $this->dropTable('accounts');

        $this->dropTable('bills');
        $this->dropTable('bills-votes');

        $this->dropTable('historical-countries');
        $this->dropTable('historical-countries-claims');
        $this->dropTable('historical-countries-to-tiles');

        $this->dropTable('dealings');
        $this->dropTable('dealings-items');

        $this->dropTable('elections');
        $this->dropTable('elections-requests-party');
        $this->dropTable('elections-requests-individual');
        $this->dropTable('elections-votes-users');
        $this->dropTable('elections-votes-pops');

        $this->dropTable('events');
        $this->dropTable('pops-requests');
        $this->dropTable('pops-requests-power');

        $this->dropTable('buildings');
        $this->dropTable('buildings-twotiled');
        $this->dropTable('units');
        $this->dropTable('auctions-unmovable-objects');
        $this->dropTable('auctions-unmovable-objects-bets');
        $this->dropTable('unmovable-objects-settings-autopurchase');
        $this->dropTable('unmovable-objects-settings-autoselling');
        $this->dropTable('pops-vacancies');

        $this->dropTable('companies');
        $this->dropTable('companies-decisions');
        $this->dropTable('companies-decisions-votes');

        $this->dropTable('invites');

        $this->dropTable('licenses');
        $this->dropTable('licenses-rules');

        $this->dropTable('massmedias');
        $this->dropTable('massmedias-subscribes');
        $this->dropTable('massmedias-posts');
        $this->dropTable('massmedias-posts-votes');
        $this->dropTable('massmedias-posts-comments');

        $this->dropTable('modifiers');

        $this->dropTable('notifications');

        $this->dropTable('agencies');

        $this->dropTable('parties');
        $this->dropTable('parties-lists');
        $this->dropTable('parties-lists-members');
        $this->dropTable('parties-lists-members-votes');
        $this->dropTable('parties-posts');

        $this->dropTable('posts');

        $this->dropTable('pops');

        $this->dropTable('regions');
        $this->dropTable('cities');
        $this->dropTable('tiles');
        $this->dropTable('tiles-resources');

        $this->dropTable('resources');
        $this->dropTable('currencies');
        $this->dropTable('resources-prices');

        $this->dropTable('taxes-buildings-holding');
        $this->dropTable('taxes-resources-selling');

        $this->dropTable('tweets');
        $this->dropTable('twitter-subscribes');
        $this->dropTable('twitter-profiles');

        $this->dropTable('users');
        $this->dropTable('users-to-parties');
        $this->dropTable('citizenships');

        $this->dropTable('military-units');

        $this->dropTable('utr');

//        echo "m160819_032658_start cannot be reverted.\n";
//
//        return false;
    }
}
