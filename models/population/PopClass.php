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
    public $color;
    
    const LUMPEN = 0;
    const WORKER = 1;

    protected static function getList()
    {
        return [
            [
                'id' => static::LUMPEN,
                'name' => Yii::t('app', 'Lumpen'),
                'color' => '#999',
            ],
            [
                'id' => static::WORKER,
                'name' => Yii::t('app', 'Worker'),
                'color' => '#f00',
            ],
        ];
    }
    
}
