<?php

use yii\db\Migration;

class m180313_184249_create_articles_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('articles', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'ownerType' => $this->integer(2)->unsigned()->notNull(),
            'ownerId' => $this->integer()->unsigned()->notNull(),
            'type' => $this->integer(5)->unsigned()->notNull(),
            'subType' => $this->integer(5)->unsigned()->null(),
            'value' => $this->string(255)->notNull(),
            'data' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('ownerConstitution', 'articles', ['ownerType', 'ownerId']);
        $this->createIndex('typeConstitution', 'articles', ['type', 'subType']);
        $this->createIndex('constitutionArticleUnique', 'articles', ['ownerType', 'ownerId', 'type', 'subType'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('ownerConstitution', 'articles');
        $this->dropIndex('typeConstitution', 'articles');
        $this->dropIndex('constitutionArticleUnique', 'articles');
        $this->dropTable('articles');
    }

}
