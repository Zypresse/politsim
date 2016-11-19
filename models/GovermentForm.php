<?php

namespace app\models;

use Yii,
    app\models\ObjectWithFixedPrototypes;

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
     * @param \app\models\State $state
     * @return integer
     */
    public function calcForState(State $state)
    {
        $constitution = $state->constitution;
        if (is_null($constitution)) {
            return static::UNKNOWN;
        }
        
        return static::DICTATURE;
    }
    
}
