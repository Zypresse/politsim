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
    
    const NOT_SET = 0;
    const COMMUNISM = 1;
    const SOCIALISM = 2;
    const SOCIAL_DEMOCRACY = 3;
    const SOCIAL_LIBERALISM = 4;

    protected static function getList()
    {
        return [
            [
                'id' => static::NOT_SET,
                'name' => Yii::t('app', 'No ideology'),
            ],
            [
                'id' => static::COMMUNISM,
                'name' => Yii::t('app', 'Communism'),
            ],
            [
                'id' => static::SOCIALISM,
                'name' => Yii::t('app', 'Socialism'),
            ],
            [
                'id' => static::SOCIAL_DEMOCRACY,
                'name' => Yii::t('app', 'Social-democracy'),
            ],
            [
                'id' => static::SOCIAL_LIBERALISM,
                'name' => Yii::t('app', 'Social-liberalism'),
            ],
        ];
    }
    
}
