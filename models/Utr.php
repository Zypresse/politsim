<?php

namespace app\models;

use app\components\MyModel,
    app\components\TaxPayer,
    app\models\User;

/**
 * Универсальный ИНН для всех платежей. Таблица "utr".
 *
 * @property integer $id
 * @property integer $objectType тип плательщика
 * @property integer $objectId ID плательщика
 * 
 * @property TaxPayer $object Владелец
 */
class Utr extends MyModel
{

    const TYPE_USER = 1;
    const TYPE_BUILDING = 2;
    const TYPE_COMPANY = 3;
    const TYPE_AGENCY = 4;
    const TYPE_PARTY = 5;
    const TYPE_POP = 6;
    const TYPE_POST = 7;
    const TYPE_REGION = 8;
    const TYPE_STATE = 9;
    const TYPE_BUILDINGTWOTILED = 10;
    const TYPE_UNIT = 11;
    
    private function typeToClass()
    {
        return [
            static::TYPE_USER => User::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'utr';
    }
    
    /**
     * 
     * @return TaxPayer
     */
    public function getObject()
    {
        $this->hasOne($this->typeToClass($this->objectType), ['id' => 'objectId']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objectType','objectId'], 'integer']
        ];
    }

}