<?php

use yii\db\Migration;

class m161216_120525_dealings_refactoring extends Migration
{
    public function up()
    {
        $this->dropTable('dealings');
        $this->createTable('dealings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'type' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiator' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'receiver' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->dropTable('dealingsItems');
        $this->createTable('dealingsItems', [
            'dealingId' => 'UNSIGNED INTEGER REFERENCES dealings(id) NOT NULL',
            'direction' => 'BOOLEAN NOT NULL', // 0 - от иницаиатора к ресиверу, 1 - от ресивера к инициатору
            'resourceId' => 'UNSIGNED INTEGER REFERENCES utr(id) DEFAULT NULL',
            'count' => 'UNSIGNED REAL NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('dealings');
        $this->createTable('dealings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'UNSIGNED INTEGER(3) NOT NULL',
            'initiator' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'receiver' => 'UNSIGNED INTEGER REFERENCES utr(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL',
            'dateApproved' => 'UNSIGNED INTEGER DEFAULT NULL'
        ]);

        $this->dropTable('dealingsItems');
        $this->createTable('dealingsItems', [
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

}
