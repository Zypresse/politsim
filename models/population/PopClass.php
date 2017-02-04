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
    const ENFORCER = 2;
    const FARMER = 3;
    const DEPENDENT = 4;
    const BUSINESSMAN = 5;
    const OFFICIAL = 6;
    const INTELLECTUAL = 7;
    const PLANKTON = 8;
    const CRIMINAL = 9;

    protected static function getList()
    {
        return [
            [
                'id' => static::LUMPEN,
                'name' => Yii::t('app', 'Lumpen'),
                'color' => '#eee',
            ],
            [
                'id' => static::WORKER,
                'name' => Yii::t('app', 'Worker'),
                'color' => '#f00',
            ],
            [
                'id' => static::ENFORCER,
                'name' => Yii::t('app', 'Enforcer'),
                'color' => '#00f',
            ],
            [
                'id' => static::FARMER,
                'name' => Yii::t('app', 'Farmer'),
                'color' => '#0e5',
            ],
            [
                'id' => static::DEPENDENT,
                'name' => Yii::t('app', 'Dependent'),
                'color' => '#e90',
            ],
            [
                'id' => static::BUSINESSMAN,
                'name' => Yii::t('app', 'Businessman'),
                'color' => '#e0e',
            ],
            [
                'id' => static::OFFICIAL,
                'name' => Yii::t('app', 'Official'),
                'color' => '#90f',
            ],
            [
                'id' => static::INTELLECTUAL,
                'name' => Yii::t('app', 'Intellectual'),
                'color' => '#f0e',
            ],
            [
                'id' => static::PLANKTON,
                'name' => Yii::t('app', 'Clerks'),
                'color' => '#8B0000',
            ],
            [
                'id' => static::CRIMINAL,
                'name' => Yii::t('app', 'Criminal'),
                'color' => '#696969',
            ],
        ];
    }
    
}
