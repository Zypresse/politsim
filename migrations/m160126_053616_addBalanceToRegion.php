<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_053616_addBalanceToRegion extends Migration
{
    public function up()
    {
        $this->addColumn("regions", "balance", "REAL NOT NULL DEFAULT (0)");
    }

    public function down()
    {
        echo "m160126_053616_addBalanceToRegion cannot be reverted.\n";

        return false;
    }

}
