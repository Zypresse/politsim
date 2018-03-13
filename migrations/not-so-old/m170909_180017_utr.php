<?php

use yii\db\Migration;

class m170909_180017_utr extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('utr', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'objectId' => $this->integer()->unsigned()->notNull(),
            'objectType' => $this->smallInteger(2)->notNull(),
        ]);
        $this->createIndex('object2utr', 'utr', ['objectId', 'objectType'], true);
        
        $this->addForeignKey('agenciesUtr', 'agencies', ['utr'], 'utr', ['id']);
        $this->addForeignKey('agenciesPostsUtr', 'agenciesPosts', ['utr'], 'utr', ['id']);
        $this->addForeignKey('citiesUtr', 'cities', ['utr'], 'utr', ['id']);
        $this->addForeignKey('regionsUtr', 'regions', ['utr'], 'utr', ['id']);
        $this->addForeignKey('statesUtr', 'states', ['utr'], 'utr', ['id']);
        $this->addForeignKey('partiesUtr', 'parties', ['utr'], 'utr', ['id']);
        $this->addForeignKey('dealingsInitiatorUtr', 'dealings', ['initiator'], 'utr', ['id']);
        $this->addForeignKey('dealingsReceiverUtr', 'dealings', ['receiver'], 'utr', ['id']);
        $this->addForeignKey('usersUtr', 'users', ['utr'], 'utr', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('agenciesUtr', 'agencies');
        $this->dropForeignKey('agenciesPostsUtr', 'agenciesPosts');
        $this->dropForeignKey('citiesUtr', 'cities');
        $this->dropForeignKey('regionsUtr', 'regions');
        $this->dropForeignKey('statesUtr', 'states');
        $this->dropForeignKey('partiesUtr', 'parties');
        $this->dropForeignKey('dealingsInitiatorUtr', 'dealings');
        $this->dropForeignKey('dealingsReceiverUtr', 'dealings');
        $this->dropForeignKey('usersUtr', 'users');
        
        $this->dropIndex('object2utr', 'utr');
        $this->dropTable('utr');
    }

}
