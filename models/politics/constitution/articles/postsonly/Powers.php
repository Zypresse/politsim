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
    
    public static function getList(): array
    {
        throw new Exception('Method '.static::className().'::getList() not overrided!');
    }
    
    public static function instantiate($row)
    {
        $className = '\\app\\models\\politics\\constitution\\articles\\postsonly\\'.([
            static::BILLS => 'Bills',
        ][(int) $row['subType']]);
        return $className::instantiate($row);
    }

}
