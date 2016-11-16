<?php

namespace app\models;

use Yii,
    app\models\ObjectWithFixedPrototypes;

/**
 * Класс нации
 *
 * @author ilya
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
