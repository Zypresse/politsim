<?php

namespace app\models;

use Yii,
    app\models\ObjectWithFixedPrototypes;

/**
 * Религия
 *
 * @author ilya
 */
class Religion extends ObjectWithFixedPrototypes
{
    public $id;
    public $name;
    
    protected static function getList()
    {
        return [
            [
                'id' => 0,
                'name' => Yii::t('app', 'No religion'),
            ],
            [
                'id' => 1,
                'name' => Yii::t('app', 'Atheism'),
            ],
            [
                'id' => 2,
                'name' => Yii::t('app', 'Orthodox'),
            ],
        ];
    }
    
}
