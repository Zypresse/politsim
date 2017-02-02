<?php

namespace app\models\economics\units;

use Yii,
    yii\base\Model;

/**
 * 
 * @property string $name
 * @property \app\models\economics\ResourcePack[] $buildResourcesPacks
 * @property \app\models\economics\LicenseProto[] $buildLicenses
 * 
 */
abstract class BaseUnitProto extends Model
{
    
    abstract public static function getList();
    
    public static function exist(int $id)
    {
        return isset(static::getList()[$id]);
    }
    
    abstract public static function getClassNameByType(int $id);
    
    /**
     * 
     * @param integer $id
     * @return \static
     */
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className;
    }
    
    abstract public function getName();
    
    abstract public function getBuildResourcesPacks();
    
    abstract public function getBuildLicenses();
    
}
