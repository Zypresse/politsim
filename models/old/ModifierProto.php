<?php

namespace app\models;

use Yii,
    app\models\base\ObjectWithFixedPrototypes,
    yii\helpers\Url;

/** 
 * Прототип модификатора
 */
class ModifierProto extends ObjectWithFixedPrototypes
{
    
    const OLDFAG = 1;
    const STATE_LEADER = 2;

    public $id;
    public $name;
    public $icon;
    
    public $fame = 0;
    public $trust = 0;
    public $success = 0;

    protected static function getList()
    {
        return [
            [
                'id' => static::OLDFAG,
                'name' => Yii::t('app', 'Oldfag'),
                'icon' => Url::to('img/user_oldman.png'),
                'success' => 1,
            ],
            [
                'id' => static::STATE_LEADER,
                'name' => Yii::t('app', 'State leader'),
                'icon' => Url::to('img/ceo.png'),
                'fame' => 3,
                'success' => 3,
            ],
        ];
    }

}
