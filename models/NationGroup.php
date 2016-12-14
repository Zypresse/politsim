<?php


namespace app\models;

use Yii;
use app\models\ObjectWithFixedPrototypes;

/**
 * 
 */
class NationGroup extends ObjectWithFixedPrototypes
{
    
    public $id;
    public $name;
    
    const NOT_SET = 0;
    const EASTERN_SLAVISH = 1;
    const WESTERN_SLAVISH = 2;
    const SEMIT = 3;
    const TURKISH = 4;
    const BALTISH = 5;

    protected static function getList()
    {
        return [
            [
                'id' => static::NOT_SET,
                'name' => Yii::t('app', 'Unknown nation group'),
            ],
            [
                'id' => static::EASTERN_SLAVISH,
                'name' => Yii::t('app', 'Eastern slavish'),
            ],
            [
                'id' => static::WESTERN_SLAVISH,
                'name' => Yii::t('app', 'Western slavish'),
            ],
            [
                'id' => static::SEMIT,
                'name' => Yii::t('app', 'Semit'),
            ],
            [
                'id' => static::TURKISH,
                'name' => Yii::t('app', 'Turkish'),
            ],
            [
                'id' => static::BALTISH,
                'name' => Yii::t('app', 'Baltish'),
            ],
        ];
    }
    
}
