<?php

use yii\db\Migration;

/**
 * Class m181103_140701_add_type_to_organizations_table
 */
class m181103_140701_add_type_to_organizations_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->addColumn('organizations', 'type', $this->integer(3)->unsigned()->notNull()->defaultValue(0));
	$this->addColumn('organizations', 'stateId', $this->integer()->unsigned()->null());
	$this->createIndex('organizationsStateIdType', 'organizations', ['stateId', 'type']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	$this->dropIndex('organizationsStateIdType', 'organizations');
	$this->dropColumn('organizations', 'type');
	$this->dropColumn('organizations', 'stateId');
    }

}
