<?php

namespace app\models\population;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * 
 */
class PopClass extends ObjectWithFixedPrototypes
{
    public $id;
    public $name;
    
    const LUMPEN = 0;

    protected static function getList()
    {
        return [
            [
                'id' => static::LUMPEN,
                'name' => Yii::t('app', 'Lumpen'),
            ],
        ];
    }
    
}
