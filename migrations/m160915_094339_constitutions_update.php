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
            'religionId' => 'UNSIGNED INTEGER(3) DEFAULT NULL'
            
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
    }

}
