<?php

namespace app\helpers;

use yii\helpers\Html;

/**
 * Description of IconHelper
 *
 * @author ilya
 */
abstract class Icon
{
    
    const STAR = ['/img/icons/star.png', 'Известность', 16];
    const HEART = ['/img/icons/heart.png', 'Доверие', 16];
    const CHARTPIE = ['/img/icons/pie-chart.png', 'Успешность', 16];
    
    const BUSINESS = ['/img/icons/business.png', 'Бизнес', 16];
    const GLOBE = ['/img/icons/globe.png', 'Карта', 16];
    const GOVERNMENT = ['/img/icons/goverment.png', 'Государство', 16];
    const MAP = ['/img/icons/map.png', 'Карта', 16];
    const NEWS = ['/img/icons/news.png', 'Новости', 16];
    const PARTY = ['/img/icons/party.png', 'Партия', 16];
    const PROFILE = ['/img/icons/profile-male.png', 'Профиль', 16];
    const PROFILE_MALE = ['/img/icons/profile-male.png', 'Профиль', 16];
    const PROFILE_FEMALE = ['/img/icons/profile-female.png', 'Профиль', 16];
    const RATING = ['/img/icons/rating.png', 'Рейтинг', 16];
    const RSS = ['/img/icons/rss.png', 'RSS', 16];
    const TV = ['/img/icons/tv.png', 'СМИ', 16];
    const WORK = ['/img/icons/work.png', 'Работа', 16];
    
    /**
     * 
     * @param array $type
     * @param array $options
     */
    public static function draw($type, $options = [])
    {
        return Html::img($type[0], array_merge(['title' => $type[1], 'style' => "width:{$type[2]}px; height: {$type[2]}px;"], $options));
    }
    
}
