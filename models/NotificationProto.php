<?php

namespace app\models;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

class NotificationProto extends ObjectWithFixedPrototypes
{
    
    const PIDOR = 0;
    const CITIZENSHIP_APPROUVED = 1;
    const CITIZENSHIP_LOST = 2;
    const MEMBERSHIP_APPROUVED = 3;
    const MEMBERSHIP_LOST = 4;
    const PARTY_CREATED = 5;
    const SETTED_TO_PARTY_POST = 6;
    const DROPPED_FROM_PARTY_POST = 7;
    const SETTED_AS_SUCCESSOR_TO_PARTY_POST = 8;
    const DELETED_PARTY_POST = 9;
    const REGISTERED_FOR_POST_ELECTIONS = 10;
    const WINNED_POST_ELECTION = 11;
    
    public $id;
    public $text;
    public $textShort;
    public $icon;
    public $iconBg;
    
    protected static function getList()
    {
        return [
            [
                'id' => static::PIDOR,
                'text' => 'Просто уведомление о том, что ты пидор.',
                'textShort' => 'Ты пидор',
                'icon' => '<i class="fa fa-envelope text-blue"></i>',
                'iconBg' => '<i class="fa fa-envelope bg-blue"></i>',
            ],
            [
                'id' => static::CITIZENSHIP_APPROUVED,
                'text' => 'Now you are a citizenship of {0}',
                'textShort' => 'Citizenship approved',
                'icon' => '<i class="fa fa-flag text-green"></i>',
                'iconBg' => '<i class="fa fa-flag bg-green"></i>',
            ],            
            [
                'id' => static::CITIZENSHIP_LOST,
                'text' => 'You have lost citizenship of {0}',
                'textShort' => 'Citizenship fired',
                'icon' => '<i class="fa fa-flag text-red"></i>',
                'iconBg' => '<i class="fa fa-flag bg-red"></i>',
            ],
            [
                'id' => static::MEMBERSHIP_APPROUVED,
                'text' => 'Your membership request to party {0} is approved',
                'textShort' => 'Membership approved',
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],            
            [
                'id' => static::MEMBERSHIP_LOST,
                'text' => 'Your have lost membership of party {0}',
                'textShort' => 'Membership fired',
                'icon' => '<i class="fa fa-group text-red"></i>',
                'iconBg' => '<i class="fa fa-group bg-red"></i>',
            ],
            [
                'id' => static::PARTY_CREATED,
                'text' => 'Party {0} successfully created',
                'textShort' => 'Party created',
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],
            [
                'id' => static::SETTED_TO_PARTY_POST,
                'text' => 'You are setted to post {0} in party {1}',
                'textShort' => 'You are setted to party post',
                'icon' => '<i class="fa fa-group text-green"></i>',
                'iconBg' => '<i class="fa fa-group bg-green"></i>',
            ],
            [
                'id' => static::DROPPED_FROM_PARTY_POST,
                'text' => 'You are dropped from post {0} in party {1}',
                'textShort' => 'Dropped from party post',
                'icon' => '<i class="fa fa-group text-red"></i>',
                'iconBg' => '<i class="fa fa-group bg-red"></i>',
            ],
            [
                'id' => static::SETTED_AS_SUCCESSOR_TO_PARTY_POST,
                'text' => 'You are setted as successor of post {0} in party {1}',
                'textShort' => 'Setted as successor of party post',
                'icon' => '<i class="fa fa-group text-blue"></i>',
                'iconBg' => '<i class="fa fa-group bg-blue"></i>',
            ],
            [
                'id' => static::DELETED_PARTY_POST,
                'text' => 'Your post {0} in party {1} was deleted',
                'textShort' => 'Your party post deleted',
                'icon' => '<i class="fa fa-group text-blue"></i>',
                'iconBg' => '<i class="fa fa-group bg-blue"></i>',
            ],
            [
                'id' => static::REGISTERED_FOR_POST_ELECTIONS,
                'text' => 'You are registered for elections to post {0} in state {1}',
                'textShort' => 'You are registered for elections',
                'icon' => '<i class="fa fa-university text-green"></i>',
                'iconBg' => '<i class="fa fa-university bg-green"></i>',
            ],
            [
                'id' => static::WINNED_POST_ELECTION,
                'text' => 'You win elections to post {0} in state {1}',
                'textShort' => 'You win elections',
                'icon' => '<i class="fa fa-university text-green"></i>',
                'iconBg' => '<i class="fa fa-university bg-green"></i>',
            ],
        ];
    }
    
}