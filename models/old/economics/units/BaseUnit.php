<?php

namespace app\models\economics\units;

use app\models\economics\TaxPayerModel,
    app\models\economics\units\Vacancy;

/**
 * Здание/трубопровод/движимое здание/армия
 * 
 * @property integer $protoId
 * @property TaxPayerModel $master
 * @property Vacancy[] $vacancies
 * 
 */
abstract class BaseUnit extends TaxPayerModel
{
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance(int $currencyId, $delta)
    {
        return $this->master->changeBalance($currencyId, $delta);
    }

    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance(int $currencyId)
    {
        return $this->master->getBalance($currencyId);
    }
    
    public function getVacancies()
    {
        $this->getUtrForced();
        return $this->hasMany(Vacancy::className(), ['objectId' => 'utr']);
    }
    
}
