<?php

namespace app\models\base\taxpayers;

/**
 * Tax payer model with UTR
 * @property integer $utr ИНН
 */
interface TaxPayerInterface
{

    /**
     * Возвращает ИНН
     * @return integer
     */
    public function getUtr();

    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance(int $currencyId);

    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance(int $currencyId, $delta);

    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType();

    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGovernment(int $stateId);

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
    public function isTaxedInState(int $stateId);

    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId();

    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController(int $userId);

}
