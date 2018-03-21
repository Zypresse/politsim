<?php

namespace app\models\base\taxpayers;

use app\models\economy\Utr;

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
        if (!$this->utr) {
            $utr = new Utr([
                'objectType' => self::getUtrType(),
                'objectId' => $this->id,
            ]);
            $utr->save();
            $this->utr = $utr->id;
        }
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
