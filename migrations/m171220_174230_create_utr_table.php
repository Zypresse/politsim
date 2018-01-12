<?php

use yii\db\Migration;

/**
 * Handles the creation of table `utr`.
 */
class m171220_174230_create_utr_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
	$this->createTable('utr', [
	    'id' => $this->primaryKey(),
	    'objectId' => $this->integer()->unsigned()->notNull(),
	    'objectType' => $this->smallInteger(2)->notNull(),
	]);
	$this->createIndex('object2utr', 'utr', ['objectId', 'objectType'], true);
	$this->addForeignKey('usersUtr', 'users', ['utr'], 'utr', ['id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
	$this->dropForeignKey('usersUtr', 'users');
	$this->dropIndex('object2utr', 'utr');
	$this->dropTable('utr');
    }

}
