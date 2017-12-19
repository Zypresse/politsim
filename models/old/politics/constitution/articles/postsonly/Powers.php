<?php

namespace app\models\politics\constitution\articles\postsonly;

use yii\base\Exception,
    app\models\politics\constitution\articles\base\BitmaskArticle;

/**
 * 
 */
class Powers extends BitmaskArticle
{
    
    const BILLS = 1;
    
    const PARTIES = 2;
    
    const LICENSES = 3;
    
    public static function getList(): array
    {
        throw new Exception('Method '.static::className().'::getList() not overrided!');
    }
    
    public static function instantiate($row)
    {
        $className = '\\app\\models\\politics\\constitution\\articles\\postsonly\\powers\\'.([
            static::BILLS => 'Bills',
            static::PARTIES => 'Parties',
            static::LICENSES => 'Licenses',
        ][(int) $row['subType']]);
        return $className::instantiate($row);
    }

}
