<?php

namespace app\models\politics;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * Форма гос.устройства
 *
 */
class StateStructure extends ObjectWithFixedPrototypes {
    
    public $id;
    public $name;
    
    const UNKNOWN = 0;
    const UNITARY = 1;
    const FEDERATION = 2;
    
    protected static function getList() {
        return [
            [
                'id' => static::UNKNOWN,
                'name' => Yii::t('app', 'Unknown structure type')
            ],
            [
                'id' => static::UNITARY,
                'name' => Yii::t('app', 'Unitary state')
            ],
            [
                'id' => static::FEDERATION,
                'name' => Yii::t('app', 'Federation')
            ]
        ];        
    }

    /**
     * Вычисляет id типа устройства государства
     * @param State $state
     * @return integer
     */
    public static function calcForState(State $state)
    {
        $constitution = $state->constitution;
        if (is_null($constitution)) {
            return static::UNKNOWN;
        }
        
        return static::UNITARY;
    }
    
}
