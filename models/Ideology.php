<?php

namespace app\models;

use Yii,
    app\models\ObjectWithFixedPrototypes;

/**
 * Идеология
 *
 * @author ilya
 */
class Ideology extends ObjectWithFixedPrototypes
{
    public $id;
    public $name;
    
    protected static function getList()
    {
        return [
            [
                'id' => 0,
                'name' => Yii::t('app', 'No ideology'),
            ],
            [
                'id' => 1,
                'name' => Yii::t('app', 'Communism'),
            ],
            [
                'id' => 2,
                'name' => Yii::t('app', 'Socialism'),
            ],
            [
                'id' => 3,
                'name' => Yii::t('app', 'Social-democracy'),
            ],
            [
                'id' => 4,
                'name' => Yii::t('app', 'Social-liberalism'),
            ],
        ];
    }
    
}
