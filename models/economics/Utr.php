<?php

namespace app\models\economics;

use app\models\base\MyActiveRecord;

/**
 * Универсальный ИНН для всех платежей. Таблица "utr".
 *
 * @property integer $id
 * @property integer $objectType тип плательщика
 * @property integer $objectId ID плательщика
 * 
 * @property TaxPayer $object Владелец
 */
class Utr extends MyActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'utr';
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
    
    /**
     * 
     * @return TaxPayer
     */
    public function getObject()
    {
        $this->hasOne(UtrType::typeToClass($this->objectType), ['id' => 'objectId']);
    }

}