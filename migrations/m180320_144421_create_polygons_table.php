<?php

use yii\db\Migration;

/**
 * Handles the creation of table `polygons`.
 */
class m180320_144421_create_polygons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('polygons', [
            'id' => $this->primaryKey(),
            'ownerType' => $this->smallInteger(2)->unsigned()->notNull(),
            'ownerId' => $this->integer()->unsigned()->notNull(),
            'data' => 'JSONB NOT NULL',
        ]);
        $this->createIndex('polygonsOwner', 'polygons', ['ownerType', 'ownerId'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('polygonsOwner', 'polygons');
        $this->dropTable('polygons');
    }
}
