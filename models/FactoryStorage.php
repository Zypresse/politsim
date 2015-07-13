<?php

namespace app\models;

use app\components\MyModel;

/**
 * This is the model class for table "factory_storages".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $resurse_id
 * @property double $count
 *
 * @property Resurse $resurse
 * @property Factory $factory
 */
class FactoryStorage extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_storages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'resurse_id', 'count'], 'required'],
            [['factory_id', 'resurse_id'], 'integer'],
            [['count'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'resurse_id' => 'Resurse ID',
            'count' => 'Count',
        ];
    }
    
    public function getResurse()
    {
        return $this->hasOne('app\models\Resurse', array('id' => 'resurse_id'));
    }

    public function getFactory()
    {
        return $this->hasOne('app\models\Factory', array('id' => 'factory_id'));
    }
}