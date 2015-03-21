<?php

namespace app\models;

use app\components\MyModel;

/**
 * Набор ресурсов для работы завода. Таблица "factory_kits".
 *
 * @property integer $id
 * @property integer $resurse_id
 * @property integer $count
 * @property integer $direction Направление: 1 - потребляемые, 2 - производимые, 3 - блокируемые
 * @property integer $type_id
 */
class FactoryKit extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_kits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resurse_id', 'count', 'direction', 'type_id'], 'required'],
            [['resurse_id', 'count', 'direction', 'type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resurse_id' => 'Resurse ID',
            'count' => 'Count',
            'direction' => '1 — in, 2 — out',
            'type_id' => 'Type ID',
        ];
    }
    
    public function getResurse()
    {
        return $this->hasOne('app\models\Resurse', array('id' => 'resurse_id'));
    }
    
    public function getType()
    {
        return $this->hasOne('app\models\FactoryType', array('id' => 'type_id'));
    }
}