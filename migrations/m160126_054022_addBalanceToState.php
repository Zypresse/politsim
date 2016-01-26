<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_054022_addBalanceToState extends Migration
{
    public function up()
    {
        $this->addColumn("states", "balance", "REAL NOT NULL DEFAULT (0)");
    }

    public function down()
    {
        echo "m160126_054022_addBalanceToState cannot be reverted.\n";

        return false;
    }
}
