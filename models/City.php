<?php

namespace app\models;

use Yii,
    app\components\MyModel,
    app\components\TaxPayer,
    app\components\LinkHelper;

/**
 * Город
 *
 * @property integer $id 
 * @property integer $regionId
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $population
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $utr
 * 
 * @property Region $region
 */
class City extends MyModel implements TaxPayer
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameShort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['flag', 'anthem'], 'string'],
            [['regionId', 'population', 'usersCount', 'usersFame', 'utr'], 'integer', 'min' => 0],
            [['anthem'], 'validateAnthem'],
            [['flag'], 'validateFlag'],
        ];
    }
    
    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return Utr::TYPE_CITY;
    }
    
    /**
     * Возвращает ИНН
     * @return int
     */
    public function getUtr()
    {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['objectId' => $this->id, 'objectType' => $this->getUtrType()]);
            if ($u) {
                $this->utr = $u->id;
                $this->save();
            }
        } 
        return $this->utr;
    }
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return $this->region && $this->region->stateId === $stateId;
    }
    
    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        return $money->count;
    }
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        $money->count += $delta;
        return $money->save();
    }
        
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->region ? $this->region->stateId : 0;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->region && $this->region->stateId === $stateId;
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return false;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController($userId)
    {
        return false;
    }    

}
