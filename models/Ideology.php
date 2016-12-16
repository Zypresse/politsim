<?php

namespace app\models;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

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
    const LIBERALISM = 5;
    const FASCISM = 6;
    const NATIONAL_DEMOCRACY = 7;
    const LIBERTARIAN = 8;
    const MONARCHISM = 9;
    const NEUTRAL = 9;
    const NAZISM = 10;
    const CONSERVATISM = 11;
    const ANARCHO_COMMUNISM = 12;
    const ANARCHISM = 13;
    const LIBERAL_CONSERVATISM = 14;
    const CHRISTIAN_DEMOCRACY = 15;
    const TECHNOCRACY = 16;
    const TECHNOCRACY_FASCISM = 17;
    const NEOCOMMUNISM = 18;
    const NATIONAL_BOLSHEVISM = 19;
    const MINARCHISM = 20;
    const MARKSISM = 21;

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
            [
                'id' => static::LIBERALISM,
                'name' => Yii::t('app', 'Liberalism'),
            ],
            [
                'id' => static::FASCISM,
                'name' => Yii::t('app', 'Fascism'),
            ],
            [
                'id' => static::NATIONAL_DEMOCRACY,
                'name' => Yii::t('app', 'National-democracy'),
            ],
            [
                'id' => static::LIBERTARIAN,
                'name' => Yii::t('app', 'Libertarian'),
            ],
            [
                'id' => static::MONARCHISM,
                'name' => Yii::t('app', 'Monarchism'),
            ],
            [
                'id' => static::NEUTRAL,
                'name' => Yii::t('app', 'Neutral'),
            ],
            [
                'id' => static::NAZISM,
                'name' => Yii::t('app', 'Nazism'),
            ],
            [
                'id' => static::CONSERVATISM,
                'name' => Yii::t('app', 'Conservarism'),
            ],
            [
                'id' => static::ANARCHO_COMMUNISM,
                'name' => Yii::t('app', 'Anarcho-communism'),
            ],
            [
                'id' => static::ANARCHISM,
                'name' => Yii::t('app', 'Anarchism'),
            ],
            [
                'id' => static::LIBERAL_CONSERVATISM,
                'name' => Yii::t('app', 'Liberal-conservatism'),
            ],
            [
                'id' => static::CHRISTIAN_DEMOCRACY,
                'name' => Yii::t('app', 'Christian democracy'),
            ],
            [
                'id' => static::TECHNOCRACY,
                'name' => Yii::t('app', 'Technocracy'),
            ],
            [
                'id' => static::TECHNOCRACY_FASCISM,
                'name' => Yii::t('app', 'Technofascism'),
            ],
            [
                'id' => static::NEOCOMMUNISM,
                'name' => Yii::t('app', 'Neocommunism'),
            ],
            [
                'id' => static::NATIONAL_BOLSHEVISM,
                'name' => Yii::t('app', 'National-bolshebism'),
            ],
            [
                'id' => static::MINARCHISM,
                'name' => Yii::t('app', 'Minarchism'),
            ],
            [
                'id' => static::MARKSISM,
                'name' => Yii::t('app', 'Marksism'),
            ],
        ];
    }
    
}
