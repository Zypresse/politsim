<?php

namespace app\models\economy;

use Yii;
use app\models\base\ActiveRecord;

/**
 * Универсальный ИНН для всех платежей. Таблица "utr".
 *
 * @property integer $id
 * @property integer $objectType тип плательщика
 * @property integer $objectId ID плательщика
 *
 * @property TaxPayer $object Владелец
 */
class Utr extends ActiveRecord
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
	    [['objectType', 'objectId'], 'integer'],
		// TODO: unique
	];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
	return $this->hasOne(UtrType::typeToClass($this->objectType), ['id' => 'objectId']);

    }

}
