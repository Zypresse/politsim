<?php

namespace app\models\economics;

/**
 * 
 * @property integer $utr ИНН
 */
interface TaxPayer {
    
    /**
     * Возвращает ИНН
     * @return integer
     */
    public function getUtr();
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId);
    
    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId);
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta);
    
    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType();
    
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId();
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId);
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId();
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController($userId);
    
}
