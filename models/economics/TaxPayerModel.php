<?php

namespace app\models\economics;

use app\models\base\MyActiveRecord;

/**
 * 
 */
abstract class TaxPayerModel extends MyActiveRecord implements TaxPayer
{
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta)
    {
        $money = Resource::findOrCreate([
            'protoId' => ResourceProto::MONEY,
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr(),
            'locationId' => $this->getUtr(),
        ], false, [
            'count' => 0
        ]);
        $money->count += $delta;
        return $money->save();
    }

    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId)
    {
        $money = Resource::findOrCreate([
            'protoId' => ResourceProto::MONEY,
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr(),
            'locationId' => $this->getUtr(),
        ], false, [
            'count' => 0
        ]);
        return $money->count;
    }

    /**
     * Возвращает ИНН
     * @return int
     */
    public function getUtr()
    {
        if (is_null($this->utr)) {
            $u = Utr::findOrCreate(['objectId' => $this->id, 'objectType' => $this->getUtrType()]);
            if ($u) {
                $this->utr = $u->id;
                $this->save();
            }
        } 
        return $this->utr;
    }

}
