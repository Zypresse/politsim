<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_051703_addBalanceToParty extends Migration
{
    public function up()
    {
        $this->addColumn("parties", "balance", "REAL NOT NULL DEFAULT (0)");
    }

    public function down()
    {
        echo "m160126_051703_addBalanceToParty cannot be reverted.\n";

        return false;
    }

}
