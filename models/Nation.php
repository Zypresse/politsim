<?php

namespace app\models;

use Yii;
use app\models\ObjectWithFixedPrototypes;
use app\models\NationGroup;

/**
 * 
 */
class Nation extends ObjectWithFixedPrototypes
{
    public $id;
    public $name;
    public $groupId;
    public $agressionBase;
    public $consciousnessBase;
    public $color;
    
    protected static function getList()
    {
        return [
            [
                'id' => 1,
                'name' => Yii::t('app', 'Without nation'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0,
                'consciousnessBase' => 0,
                'color' => '#999',
            ],
            [
                'id' => 2,
                'name' => Yii::t('app', 'Russian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
                'color' => '#f00',
            ],
            [
                'id' => 3,
                'name' => Yii::t('app', 'Belarussian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.3,
                'consciousnessBase' => 0.2,
                'color' => '#fe0',
            ],
            [
                'id' => 4,
                'name' => Yii::t('app', 'Polish'),
                'groupId' => NationGroup::WESTERN_SLAVISH,
                'agressionBase' => 0.4,
                'consciousnessBase' => 0.4,
                'color' => '#f90',
            ],
            [
                'id' => 5,
                'name' => Yii::t('app', 'Ukrainian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
                'color' => '#f0f',
            ],
            [
                'id' => 6,
                'name' => Yii::t('app', 'Jewish'),
                'groupId' => NationGroup::SEMIT,
                'agressionBase' => 0.7,
                'consciousnessBase' => 1.0,
                'color' => '#3f3',
            ],
            [
                'id' => 7,
                'name' => Yii::t('app', 'Armenians'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0.6,
                'consciousnessBase' => 0.2,
                'color' => '#f09',
            ],
            [
                'id' => 8,
                'name' => Yii::t('app', 'Romany'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0.9,
                'consciousnessBase' => 0.1,
                'color' => '#90f',
            ],
            [
                'id' => 9,
                'name' => Yii::t('app', 'Tatars'),
                'groupId' => NationGroup::TURKISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
                'color' => '#f99',
            ],
            [
                'id' => 10,
                'name' => Yii::t('app', 'Lithuanians'),
                'groupId' => NationGroup::BALTISH,
                'agressionBase' => 0.2,
                'consciousnessBase' => 0.7,
                'color' => '#0f9',
            ],
            [
                'id' => 11,
                'name' => Yii::t('app', 'Latvian'),
                'groupId' => NationGroup::BALTISH,
                'agressionBase' => 0.2,
                'consciousnessBase' => 0.8,
                'color' => '#0fe',
            ],
            [
                'id' => 12,
                'name' => Yii::t('app', 'Arabians'),
                'groupId' => NationGroup::SEMIT,
                'agressionBase' => 1.0,
                'consciousnessBase' => 0.4,
                'color' => '#e0e',
            ],
        ];
    }
    
}
