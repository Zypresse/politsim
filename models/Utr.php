<?php

namespace app\models;

use app\components\MyModel,
    app\components\TaxPayer;

/**
 * Универсальный ИНН для всех платежей. Таблица "utr".
 *
 * @property integer $id
 * @property integer $type тип плательщика
 * @property integer $p_id ID плательщика
 * 
 * @property TaxPayer $master Владелец
 */
class Utr extends MyModel
{

    const TYPE_USER = 1;
    const TYPE_FACTORY = 2;
    const TYPE_HOLDING = 3;
    const TYPE_ORG = 4;
    const TYPE_PARTY = 5;
    const TYPE_POP = 6;
    const TYPE_POST = 7;
    const TYPE_REGION = 8;
    const TYPE_STATE = 9;
    const TYPE_LINE = 10;

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
    public function getMaster()
    {
        switch ($this->type) {
            case static::TYPE_USER:
                return $this->hasOne('app\models\User', ['id' => 'p_id']);
            case static::TYPE_HOLDING:
                return $this->hasOne('app\models\Holding', ['id' => 'p_id']);
            case static::TYPE_STATE:
                return $this->hasOne('app\models\State', ['id' => 'p_id']);
            case static::TYPE_FACTORY:
                return $this->hasOne('app\models\factories\Factory', ['id' => 'p_id']);
            case static::TYPE_ORG:
                return $this->hasOne('app\models\Org', ['id' => 'p_id']);
            case static::TYPE_PARTY:
                return $this->hasOne('app\models\Party', ['id' => 'p_id']);
            case static::TYPE_POP:
                return $this->hasOne('app\models\Population', ['id' => 'p_id']);
            case static::TYPE_POST:
                return $this->hasOne('app\models\Post', ['id' => 'p_id']);
            case static::TYPE_REGION:
                return $this->hasOne('app\models\Region', ['id' => 'p_id']);
            case static::TYPE_LINE:
                return $this->hasOne('app\models\factories\Line', ['id' => 'p_id']);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','p_id'], 'integer']
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
            'p_id' => 'Payer Id',
        ];
    }

    public static function findOneOrCreate($fields)
    {
        $u = self::find()->where($fields)->one();
        if (is_null($u)) {
            $u = new Utr($fields);
            if (!$u->save()) {
                $u = null;
            }
        }

        return $u;
    }
}