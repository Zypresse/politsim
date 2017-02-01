<?php

use yii\db\Migration;

class m170201_092403_update_units extends Migration
{
    public function safeUp()
    {
        $this->dropTable('buildings');
        $this->createTable('buildings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'size' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            'deterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания, 1 — новое, 0 — убитое в хлам
            
            // от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyTile' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от региона
            'efficiencyCompany' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от компании

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateBuilded' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (ID задания)
            'taskId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'taskSubId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поле для регулировки баланса количество/качество. от 0 до 1. 0 — макс. количество, мин. качество; 1 — мин. количество, макс. качество
            'taskFactor' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('buildingMaster', 'buildings', ['masterId']);
        $this->createIndex('buildingTile', 'buildings', ['tileId']);
        $this->createIndex('buildingDateCreated', 'buildings', ['dateCreated']);
        $this->createIndex('buildingDateDeleted', 'buildings', ['dateDeleted']);
        $this->createIndex('buildingUTR', 'buildings', ['utr'], true);
    
        $this->dropTable('buildingsTwotiled');
        $this->createTable('buildingsTwotiled', [            
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'masterId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'tile2Id' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',

            'name' => 'VARCHAR(255) NOT NULL',
            'nameShort' => 'VARCHAR(6) NOT NULL',
            'size' => 'UNSIGNED INTEGER(4) NOT NULL',
            
            'deterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания, 1 — новое, 0 — убитое в хлам
            
            // от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyTile' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от региона
            'efficiencyCompany' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от компании

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateBuilded' => 'UNSIGNED INTEGER DEFAULT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (ID задания)
            'taskId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'taskSubId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поле для регулировки баланса количество/качество. от 0 до 1. 0 — макс. количество, мин. качество; 1 — мин. количество, макс. качество
            'taskFactor' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('buildingsTwotiledMaster', 'buildingsTwotiled', ['masterId']);
        $this->createIndex('buildingsTwotiledTile', 'buildingsTwotiled', ['tileId']);
        $this->createIndex('buildingsTwotiledTile2', 'buildingsTwotiled', ['tile2Id']);
        $this->createIndex('buildingsTwotiledTiles', 'buildingsTwotiled', ['tileId', 'tile2Id']);
        $this->createIndex('buildingsTwotiledDateCreated', 'buildingsTwotiled', ['dateCreated']);
        $this->createIndex('buildingsTwotiledDateDeleted', 'buildingsTwotiled', ['dateDeleted']);
        $this->createIndex('buildingsTwotiledUTR', 'buildingsTwotiled', ['utr'], true);

        $this->dropTable('units');
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
                        
            // от 0 до 1, 0 — нулевая, 1 — идеальная
            'efficiencyWorkersCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // число рабочих
            'efficiencyWorkersСontentment' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // удовлетворённость рабочих
            'efficiencyEquipmentCount' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // количество оборудования
            'efficiencyEquipmentQuality' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // качество оборудования
            'efficiencyBuildingDeterioration' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // износ здания
            'efficiencyTile' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от региона
            'efficiencyCompany' => 'UNSIGNED REAL NOT NULL DEFAULT 1', // эффективность от компании

            // дата постройки
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateDeleted' => 'UNSIGNED INTEGER DEFAULT NULL',

            // управляющий
            'managerId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
            // статус работы
            'status' => 'UNSIGNED INTEGER(2) NOT NULL DEFAULT 0',
            // текущее задание (ID задания)
            'taskId' => 'UNSIGNED INTEGER(4) DEFAULT NULL',
            'taskSubId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // поле для регулировки баланса количество/качество. от 0 до 1. 0 — макс. количество, мин. качество; 1 — мин. количество, макс. качество
            'taskFactor' => 'UNSIGNED REAL DEFAULT 0.5',

            // UTR - аналог ИНН для идентификации физ. и юр. лиц
            'utr' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('unitsMaster', 'units', ['masterId']);
        $this->createIndex('unitsDateCreated', 'units', ['dateCreated']);
        $this->createIndex('unitsDateDeleted', 'units', ['dateDeleted']);
        $this->createIndex('unitsUTR', 'units', ['utr'], true);
        
        $this->dropTable('auctions');
        $this->createTable('auctions', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'objectId' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',

            // валюта
            'currencyId' => 'UNSIGNED INTEGER REFERENCES currencies(id) NOT NULL',
            'priceStart' => 'UNSIGNED REAL NOT NULL',
            'priceCurrent' => 'UNSIGNED REAL DEFAULT NULL',
            'priceStop' => 'UNSIGNED REAL DEFAULT NULL',

            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateBetingFinished' => 'UNSIGNED INTEGER NOT NULL',
            'dateFinished' => 'UNSIGNED INTEGER DEFAULT NULL',

            'winner' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL'
        ]);
        $this->createIndex('auctionsObject', 'auctions', ['objectId']);
        $this->createIndex('auctionsDateCreated', 'auctions', ['dateCreated']);
        $this->createIndex('auctionsDateBetingFinished', 'auctions', ['dateBetingFinished']);
        $this->createIndex('auctionsDateFinished', 'auctions', ['dateFinished']);
        
        $this->dropTable('popsVacancies');
    }

    public function safeDown()
    {
        $this->dropTable('buildings');
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
    
        $this->dropTable('buildingsTwotiled');
        $this->createTable('buildingsTwotiled', [            
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

        $this->dropTable('units');
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
        
        $this->dropTable('auctions');
        $this->createTable('auctions', [
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

        $this->createTable('popsVacancies', [
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
        $this->createIndex('popsVacanciesPrimary', 'popsVacancies', ['objectId', 'popClassId'], true);

    }
}
