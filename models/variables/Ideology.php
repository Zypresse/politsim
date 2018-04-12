<?php

namespace app\models\variables;

use yii2tech\filedb\ActiveRecord;

/**
 * Идеология
 *
 * @author ilya
 * @property integer $id
 * @property string $name
 */
class Ideology extends ActiveRecord
{
    
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
    
    public static function fileName()
    {
        return 'ideology';
    }
    
}
