<?php

use yii\db\Migration;

class m170903_155240_agencies2posts extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('agencies2posts', [
            'postId' => $this->integer()->unsigned()->notNull(),
            'agencyId' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->createIndex('agency2post', 'agencies2posts', ['postId', 'agencyId'], true);
    }

    public function safeDown()
    {
        $this->dropTable('agencies2posts');
    }
    
}
