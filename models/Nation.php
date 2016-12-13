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
    
    
    protected static function getList()
    {
        return [
            [
                'id' => 1,
                'name' => Yii::t('app', 'Without nation'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0,
                'consciousnessBase' => 0,
            ],
            [
                'id' => 2,
                'name' => Yii::t('app', 'Russian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
            ],
            [
                'id' => 3,
                'name' => Yii::t('app', 'Belarussian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.3,
                'consciousnessBase' => 0.2,
            ],
            [
                'id' => 4,
                'name' => Yii::t('app', 'Polish'),
                'groupId' => NationGroup::WESTERN_SLAVISH,
                'agressionBase' => 0.4,
                'consciousnessBase' => 0.4,
            ],
            [
                'id' => 5,
                'name' => Yii::t('app', 'Ukrainian'),
                'groupId' => NationGroup::EASTERN_SLAVISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
            ],
            [
                'id' => 6,
                'name' => Yii::t('app', 'Jewish'),
                'groupId' => NationGroup::SEMIT,
                'agressionBase' => 0.7,
                'consciousnessBase' => 1.0,
            ],
            [
                'id' => 7,
                'name' => Yii::t('app', 'Armenians'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0.6,
                'consciousnessBase' => 0.2,
            ],
            [
                'id' => 8,
                'name' => Yii::t('app', 'Romany'),
                'groupId' => NationGroup::NOT_SET,
                'agressionBase' => 0.9,
                'consciousnessBase' => 0.1,
            ],
            [
                'id' => 9,
                'name' => Yii::t('app', 'Tatars'),
                'groupId' => NationGroup::TURKISH,
                'agressionBase' => 0.7,
                'consciousnessBase' => 0.1,
            ],
            [
                'id' => 10,
                'name' => Yii::t('app', 'Lithuanians'),
                'groupId' => NationGroup::BALTISH,
                'agressionBase' => 0.2,
                'consciousnessBase' => 0.7,
            ],
            [
                'id' => 11,
                'name' => Yii::t('app', 'Latvian'),
                'groupId' => NationGroup::BALTISH,
                'agressionBase' => 0.2,
                'consciousnessBase' => 0.8,
            ],
            [
                'id' => 12,
                'name' => Yii::t('app', 'Arabians'),
                'groupId' => NationGroup::SEMIT,
                'agressionBase' => 1.0,
                'consciousnessBase' => 0.4,
            ],
        ];
    }
    
}
