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
    public $color;
    public $contentmentFactor = 1;
    public $agressionFactor = 1;
    public $consciousnessFactor = 1;
    
    const NONRELIGIOUS = 0;
    const ORTHODOX = 1;
    const CATHOLICISM = 2;
    const PROTESTANTISM = 3;
    const OTHER_CHRISTIAN = 4;
    const SUNNI_ISLAM = 5;
    const SHIA_ISLAM = 6;
    const TENGRI = 7;
    const BUDDHISM = 8;
    const JUDAISM = 9;
    const HINDUISM = 10;
    const TAOISM = 11;
    const FOLK_RELIGION = 12;
    const PASTAFARIANISM = 13;
    const SIKHISM = 14;
    const ZOROASTRIANISM = 15;
    const JAINISM = 16;
    const BAHAI = 17;

    protected static function getList()
    {
        return [
            [
                'id' => static::NONRELIGIOUS,
                'name' => Yii::t('app', 'Nonreligious'),
                'color' => '#aaaaaa',
            ],
            [
                'id' => static::ORTHODOX,
                'name' => Yii::t('app', 'Orthodox'),
                'color' => '#9932CC',
                'contentmentFactor' => 2.0,
                'agressionFactor' => 1.5,
                'consciousnessFactor' => 0.5,
            ],
            [
                'id' => static::CATHOLICISM,
                'name' => Yii::t('app', 'Catholicism'),
                'color' => '#ff0000',
                'contentmentFactor' => 1,
                'agressionFactor' => 1.3,
                'consciousnessFactor' => 0.7,
            ],
            [
                'id' => static::PROTESTANTISM,
                'name' => Yii::t('app', 'Protestantism'),
                'color' => '#0645ad',
                'contentmentFactor' => 1,
                'agressionFactor' => 1.2,
                'consciousnessFactor' => 0.8,
            ],
            [
                'id' => static::OTHER_CHRISTIAN,
                'name' => Yii::t('app', 'Other christian'),
                'color' => '#0645ad',
                'contentmentFactor' => 1.5,
                'agressionFactor' => 1.4,
                'consciousnessFactor' => 0.6,
            ],
            [
                'id' => static::SUNNI_ISLAM,
                'name' => Yii::t('app', 'Sunni islam'),
                'color' => '#006e22',
                'contentmentFactor' => 2.5,
                'agressionFactor' => 1.7,
                'consciousnessFactor' => 0.3,
            ],
            [
                'id' => static::SHIA_ISLAM,
                'name' => Yii::t('app', 'Shi`a islam'),
                'color' => '#e09e12',
                'contentmentFactor' => 2.5,
                'agressionFactor' => 1.9,
                'consciousnessFactor' => 0.1,
            ],
            [
                'id' => static::TENGRI,
                'name' => Yii::t('app', 'Tengri'),
                'color' => '#adff2f',
                'contentmentFactor' => 1,
                'agressionFactor' => 1.5,
                'consciousnessFactor' => 0.5,
            ],
            [
                'id' => static::BUDDHISM,
                'name' => Yii::t('app', 'Buddhism'),
                'color' => '#7fff00',
                'contentmentFactor' => 3.0,
                'agressionFactor' => 1.1,
                'consciousnessFactor' => 0.9,
            ],
            [
                'id' => static::JUDAISM,
                'name' => Yii::t('app', 'Judaism'),
                'color' => '#0038b8',
                'contentmentFactor' => 0.5,
                'agressionFactor' => 1.5,
                'consciousnessFactor' => 1.5,
            ],
            [
                'id' => static::HINDUISM,
                'name' => Yii::t('app', 'Hinduism'),
                'color' => '#FA0002',
                'contentmentFactor' => 2.0,
                'agressionFactor' => 1.2,
                'consciousnessFactor' => 0.8,
            ],
            [
                'id' => static::TAOISM,
                'name' => Yii::t('app', 'Taoism'),
                'color' => '#333',
                'contentmentFactor' => 3.0,
                'agressionFactor' => 0.2,
                'consciousnessFactor' => 0.8,
            ],
            [
                'id' => static::FOLK_RELIGION,
                'name' => Yii::t('app', 'Folk religion'),
                'color' => '#962fbf',
                'contentmentFactor' => 2.0,
                'agressionFactor' => 2.0,
                'consciousnessFactor' => 0.1,
            ],
            [
                'id' => static::PASTAFARIANISM,
                'name' => Yii::t('app', 'Pastafarianism'),
                'color' => '#6C412F',
                'contentmentFactor' => 0.9,
                'agressionFactor' => 0,
                'consciousnessFactor' => 100.0,
            ],
            [
                'id' => static::SIKHISM,
                'name' => Yii::t('app', 'Sikhism'),
                'color' => '#FF9900',
                'contentmentFactor' => 2.0,
                'agressionFactor' => 1.5,
                'consciousnessFactor' => 0.5,
            ],
            [
                'id' => static::ZOROASTRIANISM,
                'name' => Yii::t('app', 'Zoroastrianism'),
                'color' => '#B8860B',
                'contentmentFactor' => 1.5,
                'agressionFactor' => 1.2,
                'consciousnessFactor' => 0.8,
            ],
            [
                'id' => static::JAINISM,
                'name' => Yii::t('app', 'Jainism'),
                'color' => '#F14A1C',
                'contentmentFactor' => 3.0,
                'agressionFactor' => 0.3,
                'consciousnessFactor' => 0.5,
            ],
            [
                'id' => static::BAHAI,
                'name' => Yii::t('app', 'Bahai'),
                'color' => '#555',
                'contentmentFactor' => 2.0,
                'agressionFactor' => 1.5,
                'consciousnessFactor' => 0.5,
            ],
        ];
    }
    
}
