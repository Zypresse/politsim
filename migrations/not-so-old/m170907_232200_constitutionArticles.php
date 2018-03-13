<?php

use yii\db\Migration;

class m170907_232200_constitutionArticles extends Migration
{
    public function safeUp()
    {
        $this->createTable('constitutionArticles', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'ownerType' => $this->integer(2)->unsigned()->notNull(),
            'ownerId' => $this->integer()->unsigned()->notNull(),
            'type' => $this->integer(5)->unsigned()->notNull(),
            'subType' => $this->integer(5)->unsigned()->null(),
            'value' => $this->string(255)->notNull(),
            'value2' => $this->string(255)->null(),
            'value3' => $this->string(255)->null(),
            'data' => 'JSONB DEFAULT NULL',
        ]);
        $this->createIndex('ownerConstitution', 'constitutionArticles', ['ownerType', 'ownerId']);
        $this->createIndex('typeConstitution', 'constitutionArticles', ['type', 'subType']);
        $this->createIndex('constitutionArticleUnique', 'constitutionArticles', ['ownerType', 'ownerId', 'type', 'subType'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('ownerConstitution', 'constitutionArticles');
        $this->dropIndex('typeConstitution', 'constitutionArticles');
        $this->dropIndex('constitutionArticleUnique', 'constitutionArticles');
        $this->dropTable('constitutionArticles');
    }

}
