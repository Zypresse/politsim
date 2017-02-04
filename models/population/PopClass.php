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
    public $icon;
    
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
                'icon' => '',
            ],
            [
                'id' => static::WORKER,
                'name' => Yii::t('app', 'Worker'),
                'color' => '#f00',
                'icon' => '/img/pop-classes/Worker.png',
            ],
            [
                'id' => static::ENFORCER,
                'name' => Yii::t('app', 'Enforcer'),
                'color' => '#00f',
                'icon' => '/img/pop-classes/Enforcer.png',
            ],
            [
                'id' => static::FARMER,
                'name' => Yii::t('app', 'Farmer'),
                'color' => '#0e5',
                'icon' => '/img/pop-classes/Farmer.png',
            ],
            [
                'id' => static::DEPENDENT,
                'name' => Yii::t('app', 'Dependent'),
                'color' => '#e90',
                'icon' => '/img/pop-classes/Dependent.png',
            ],
            [
                'id' => static::BUSINESSMAN,
                'name' => Yii::t('app', 'Businessman'),
                'color' => '#e0e',
                'icon' => '/img/pop-classes/Businessman.png',
            ],
            [
                'id' => static::OFFICIAL,
                'name' => Yii::t('app', 'Official'),
                'color' => '#90f',
                'icon' => '',
            ],
            [
                'id' => static::INTELLECTUAL,
                'name' => Yii::t('app', 'Intellectual'),
                'color' => '#f0e',
                'icon' => '/img/pop-classes/Intellectual.png',
            ],
            [
                'id' => static::PLANKTON,
                'name' => Yii::t('app', 'Clerks'),
                'color' => '#8B0000',
                'icon' => '',
            ],
            [
                'id' => static::CRIMINAL,
                'name' => Yii::t('app', 'Criminal'),
                'color' => '#696969',
                'icon' => '/img/pop-classes/Criminal.png',
            ],
        ];
    }
    
}
