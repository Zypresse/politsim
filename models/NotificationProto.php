<?php

namespace app\models;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

class NotificationProto extends ObjectWithFixedPrototypes
{    
    public $id;
    public $text;
    public $textShort;
    public $icon;
    public $iconBg;
    
    private static function getList()
    {
        return [
            [
                'id' => 0,
                'text' => Yii::t('app', 'Просто уведомление о том, что ты пидор.'),
                'textShort' => Yii::t('app', 'Ты пидор'),
                'icon' => '<i class="fa fa-envelope text-blue"></i>',
                'iconBg' => '<i class="fa fa-envelope bg-blue"></i>',
            ],
            [
                'id' => 1,
                'text' => Yii::t('app', 'Your citizenship request is approved'),
                'textShort' => Yii::t('app', 'Citizenship approved'),
                'icon' => '<i class="fa fa-flag text-green"></i>',
                'iconBg' => '<i class="fa fa-flag bg-green"></i>',
            ],            
            [
                'id' => 2,
                'text' => Yii::t('app', 'Your have lost citizehship'),
                'textShort' => Yii::t('app', 'Citizenship fired'),
                'icon' => '<i class="fa fa-flag text-red"></i>',
                'iconBg' => '<i class="fa fa-flag bg-red"></i>',
            ],
            [
                'id' => 3,
                'text' => Yii::t('app', 'Your membership request is approved'),
                'textShort' => Yii::t('app', 'Membership approved'),
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],            
            [
                'id' => 4,
                'text' => Yii::t('app', 'Your have lost membership'),
                'textShort' => Yii::t('app', 'Membership fired'),
                'icon' => '<i class="fa fa-group text-red"></i>',
                'iconBg' => '<i class="fa fa-group bg-red"></i>',
            ],
            [
                'id' => 5,
                'text' => Yii::t('app', 'Party successfully created'),
                'textShort' => Yii::t('app', 'Party created'),
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],
            [
                'id' => 6,
                'text' => Yii::t('app', 'You are setted to new party post'),
                'textShort' => Yii::t('app', 'Setting to party post'),
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],
            [
                'id' => 7,
                'text' => Yii::t('app', 'You are dropped from party post'),
                'textShort' => Yii::t('app', 'Dropped from party post'),
                'icon' => '<i class="fa fa-group text-red"></i>',
                'iconBg' => '<i class="fa fa-group bg-red"></i>',
            ],
            [
                'id' => 8,
                'text' => Yii::t('app', 'You are setted as successor of party post'),
                'textShort' => Yii::t('app', 'Setted as successor of party post'),
                'icon' => '<i class="fa fa-group text-blue"></i>',
                'iconBg' => '<i class="fa fa-group bg-blue"></i>',
            ],
            [
                'id' => 9,
                'text' => Yii::t('app', 'Your party post was deleted'),
                'textShort' => Yii::t('app', 'Your party post deleted'),
                'icon' => '<i class="fa fa-group text-blue"></i>',
                'iconBg' => '<i class="fa fa-group bg-blue"></i>',
            ],
        ];
    }
    
}