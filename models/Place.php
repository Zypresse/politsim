<?php

namespace app\models;

use app\components\MyModel,
    app\models\factories\Factory,
    app\components\TaxPayer;

/**
 * This is the model class for table "places".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $object_id
 * 
 * @property TaxPayer $object
 */
class Place extends MyModel
{
    
    const TYPE_FACTORY = 1;
    const TYPE_POP = 2;
    const TYPE_REGION = 3;
    const TYPE_LINE = 4;
    const TYPE_RESURSE = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'object_id'], 'required'],
            [['type', 'object_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'object_id' => 'Object ID',
        ];
    }
    
    
    public function getObject()
    {
        switch ($this->type) {
            case static::TYPE_FACTORY:
                return $this->hasOne(Factory::className(), ['id' => 'object_id']);
        }
    }
}
