<?php

namespace app\models\economics;

use Yii;

/**
 * 
 */
abstract class CompanyDecisionProto
{
    
    /**
     * Назначить директора
     */
    const SET_DIRECTOR = 1;
        
    public static function findAll()
    {
        return [
            static::SET_DIRECTOR => Yii::t('app', 'Set director'),
        ];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassNameByType(int $type) : string
    {
        $classes = [
            static::SET_DIRECTOR => 'SetDirector',
        ];
        
        return '\\app\\models\\economics\\decisions\\'.$classes[$type];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getViewByType(int $type) : string
    {
        $views = [
            static::SET_DIRECTOR => 'set-director',
        ];
        
        return '/company/decisions/'.$views[$type];
    }
    
    /**
     * 
     * @param integer $id
     * @return \static
     */
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className();
    }
    
    /**
     * 
     * @param CompanyDecision $decision
     * @return array
     */
    public function getDefaultData(CompanyDecision $decision)
    {
        return [];
    }
    
    /**
     * 
     * @param CompanyDecision $decision
     * @return string
     */
    public function renderFull(CompanyDecision $decision) : string
    {
        return $this->render($decision);
    }
    
    public static function isAvailable(Company $company) : bool
    {
        return true;
    }
    
    public static function exist(int $protoId) : bool
    {
        return isset(static::findAll()[$protoId]);
    }
        
    abstract public function render(CompanyDecision $decision) : string;
    abstract public function validate(CompanyDecision $decision) : bool;
    abstract public function accept(CompanyDecision $decision) : bool;
    
}
