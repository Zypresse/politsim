<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_044531_addBalanceToOrg extends Migration
{
    public function up()
    {
        $this->addColumn("orgs", "balance", "REAL NOT NULL DEFAULT (0)");
    }

    public function down()
    {
        echo "m160126_044531_addBalanceToOrg cannot be reverted.\n";

        return false;
    }

}
