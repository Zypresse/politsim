<?php

namespace app\models\politics;

use Yii,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\statesonly\Parties,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * Форма правления
 *
 */
class GovermentForm extends ObjectWithFixedPrototypes {
    
    public $id;
    public $name;
    
    const UNKNOWN = 0;
    const REPUBLIC = 1;
    const DICTATURE = 2;
    
    protected static function getList() {
        return [
            [
                'id' => static::UNKNOWN,
                'name' => Yii::t('app', 'Unknown goverment form')
            ],
            [
                'id' => static::REPUBLIC,
                'name' => Yii::t('app', 'Republic')
            ],
            [
                'id' => static::DICTATURE,
                'name' => Yii::t('app', 'Dictature')
            ]
        ];        
    }

    /**
     * Вычисляет id формы правления государства
     * @param State $state
     * @return integer
     */
    public static function calcForState(State &$state)
    {
        if ($state->constitution->getArticleByType(ConstitutionArticleType::PARTIES)->value == Parties::ALLOWED) {
            return static::REPUBLIC;
        } else {
            return static::DICTATURE;
        }
    }
    
}
