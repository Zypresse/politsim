<?php

namespace app\models;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * Религия
 *
 * @author ilya
 */
class Religion extends ObjectWithFixedPrototypes
{
    public $id;
    public $name;
    
    const NOT_SET = 0;
    const ATHEISM = 1;
    const ORTHODOX = 2;

    protected static function getList()
    {
        return [
            [
                'id' => static::NOT_SET,
                'name' => Yii::t('app', 'No religion'),
            ],
            [
                'id' => static::ATHEISM,
                'name' => Yii::t('app', 'Atheism'),
            ],
            [
                'id' => static::ORTHODOX,
                'name' => Yii::t('app', 'Orthodox'),
            ],
        ];
    }
    
}
