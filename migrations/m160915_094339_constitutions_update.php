<?php

use yii\db\Migration;

class m160915_094339_constitutions_update extends Migration
{
    public function up()
    {
        $this->dropTable('constitutions');
        
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
        
        $this->createTable('constitutions-licenses', [
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
        $this->createIndex('stateToLicense', 'constitutions-licenses', ['stateId', 'licenseProtoId'], true);
        
        $this->dropTable('constitutions-agencies');
        
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
        
        $this->createTable('constitutions-agencies-licenses', [
            'agencyId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES agencies(id) NOT NULL',
            'licenseProtoId' => 'UNSIGNED INTEGER NOT NULL',
            // права (bitmask)
            // 1 - заниматься деятельностью по лицензии
            // 2 - выдавать лицензии
            // 4 - продлять лицензии
            // 8 - отзывать лицензии
            'powers' => 'UNSIGNED INTEGER(2) NOT NULL',
        ]);
        
        $this->dropTable('constitutions-regions');
        
        $this->createTable('constitutions-regions', [
            'regionId' => 'UNSIGNED INTEGER PRIMARY KEY REFERENCES regions(id) NOT NULL',

            // пост формального лидера региона
            'leaderPostId' => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            
        ]);

    }

    public function down()
    {
        $this->dropTable('constitutions');
        
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
                
        $this->dropTable('constitutions-regions');
        
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
        
        $this->dropTable('constitutions-agencies');
        
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
            'agencyBusinessPolicy' => 'UNSIGNED INTEGER(1) NOT NULL',
                        
            // управление выдачей лицензий (bitmask)
            // 1 — добыча нефтегаз
            // 2 — добыча уголь
            // 4 - добыча цветмет
            // 8 - добыча железо
            // 16 - добыча редкозем
            // 32 - добыча уран
            // 64 - добыча дерево
            // 128 - сельхоз
            // 256 - добыча строймат
            // 512 - тэс
            // 1024 - альт энергентика
            // 2048 - атомн энергетика
            // 4096 - банки
            // 8192 - нефтепереработка
            // 16384 - обработка цветмет
            // 32768 - обработка железо
            // 65536 - обработка редкозем
            // 131072 - обработка уран
            // 262144 - обработка дерево
            // 524288 - обработка строймат
            // 1048576 - станки и приборы
            // 2097152 - двс
            // 4194304 - джеты
            // 8388608 - электроника
            // 16777216 - трубопрокат
            // 33554432 - аккумуляторы
            // 67108864 - одежда и обувь
            // 134217728 - алкоголь
            // 268435456 - мебель
            // 536870912 - розничная торговля
            // 1073741824 - пресса
            // 2147483648 - телерадио
            'licensesControlling' => 'UNSIGNED INTEGER DEFAULT NULL'

        ]);
    }

}
