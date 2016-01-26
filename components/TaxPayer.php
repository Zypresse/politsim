<?php

namespace app\components;

/**
 * 
 *
 * @author ilya
 * 
 * @property int $unnp ИНН
 */
interface TaxPayer {
    
    /**
     * Возвращает ИНН
     * @return int
     */
    public function getUnnp();
    
    /*public function getStocks();
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }*/
    
    /**
     * Является ли плательщик правительством страны
     * @param int $stateId
     * @return boolean
     */
    public function isGoverment($stateId);
    
    /**
     * Возвращает баланс плательщика в у.е.
     * @return double
     */
    public function getBalance();
    
    /**
     * Меняет баланс плательщика BUT NOT SAVED IT
     * @param double $delta
     */
    public function changeBalance($delta);
    
    /**
     * Возвращает название плательщика в HTML
     * @return string
     */
    public function getHtmlName();
    
    /**
     * Возвращает константное значение типа налогоплательщика
     * @return int
     */
    public function getUnnpType();
    
    /**
     * ID страны, в которой он платит налоги
     * @return int
     */
    public function getTaxStateId();
    
    /**
     * Является ли налогоплательщиком государства
     * @param int $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId);
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId();
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param int $userId
     * @return boolean
     */
    public function isUserController($userId);
    
}
