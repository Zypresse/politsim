<?php

namespace app\models\economy;

/**
 * Description of TaxPayerTrait
 *
 * @author ilya
 */
trait TaxPayerTrait
{

    /**
     * Возвращает ИНН
     * @return integer
     */
    public function getUtr(): int
    {
	return $this->utr;
    }

    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance(int $currencyId)
    {
	return 0;
    }

    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance(int $currencyId, $delta)
    {

    }

}
