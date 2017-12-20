<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m171220_165731_create_users_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
	$this->createTable('users', [
	    'id' => $this->primaryKey(),
	    'accountId' => $this->integer()->unsigned()->notNull(),
	    'name' => $this->string(255)->notNull(),
	    'avatar' => $this->text()->null(),
	    'avatarBig' => $this->text()->null(),
	    'gender' => $this->smallInteger(1)->notNull()->defaultValue(0),
	    'tileId' => $this->integer()->unsigned()->null(),
	    'ideologyId' => $this->smallInteger(4)->unsigned()->null(),
	    'fame' => $this->integer()->notNull()->defaultValue(0),
	    'trust' => $this->integer()->notNull()->defaultValue(0),
	    'success' => $this->integer()->notNull()->defaultValue(0),
	    'fameBase' => $this->integer()->notNull()->defaultValue(0),
	    'trustBase' => $this->integer()->notNull()->defaultValue(0),
	    'successBase' => $this->integer()->notNull()->defaultValue(0),
	    'dateCreated' => $this->integer()->unsigned()->notNull(),
	    'utr' => $this->integer()->unsigned()->unique()->null(),
	]);
	$this->createIndex('usersAccountId', 'users', ['accountId']);
	$this->createIndex('usersName', 'users', ['name']);
	$this->createIndex('usersGender', 'users', ['gender']);
	$this->createIndex('usersTileId', 'users', ['tileId']);
	$this->createIndex('usersIdeologyId', 'users', ['ideologyId']);
	$this->createIndex('usersFame', 'users', ['fame']);
	$this->createIndex('usersTrust', 'users', ['trust']);
	$this->createIndex('usersSuccess', 'users', ['success']);
	$this->createIndex('usersDateCreated', 'users', ['dateCreated']);
	$this->createIndex('usersUtr', 'users', ['utr'], true);
	$this->addForeignKey('usersAccountIdRef', 'users', ['accountId'], 'accounts', ['id']);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
	$this->dropTable('users');

    }

}
