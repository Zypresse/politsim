<?php

use yii\db\Migration;

/**
 * Handles the creation of table `accounts`.
 */
class m171220_164020_create_accounts_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {

	$this->createTable('accounts', [
	    'id' => $this->primaryKey(),
	    'email' => $this->string(256)->unique()->notNull(),
	    'password' => $this->string(512)->defaultValue(null),
	    'accessToken' => $this->string(255)->unique()->notNull(),
	    'role' => $this->smallInteger(2)->unsigned()->notNull()->defaultValue(1),
	    'status' => $this->smallInteger(2)->unsigned()->notNull()->defaultValue(0),
	    'dateCreated' => $this->integer()->unsigned()->notNull(),
	    'dateExpected' => $this->integer()->unsigned()->defaultValue(null),
	    'activeUserId' => $this->integer()->defaultValue(null),
	]);

	$this->createIndex('accountsEmail', 'accounts', ['email']);
	$this->createIndex('accountsStatus', 'accounts', ['status']);
	$this->createIndex('accountsRole', 'accounts', ['role']);
	$this->createIndex('accountsDateCreated', 'accounts', ['dateCreated']);
	$this->createIndex('accountsDateExpected', 'accounts', ['dateExpected']);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

	$this->dropIndex('accountsEmail', 'accounts');
	$this->dropIndex('accountsStatus', 'accounts');
	$this->dropIndex('accountsRole', 'accounts');
	$this->dropIndex('accountsDateCreated', 'accounts');
	$this->dropIndex('accountsDateExpected', 'accounts');

	$this->dropTable('accounts');

    }

}
