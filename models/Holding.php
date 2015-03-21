<?php

namespace app\models;

use app\components\MyModel;

/**
 * Акционерное общество. Таблица "holdings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property integer $region_id
 * @property double $capital
 * 
 * @property Stock[] $stocks Акции
 * @property HoldingLicense[] $licenses Лицензии
 */
class Holding extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holdings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'state_id'], 'required'],
            [['state_id', 'region_id'], 'integer'],
            [['capital'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'state_id' => 'State ID',
            'region_id' => 'Region ID',
            'capital' => 'Капитализация',
        ];
    }
    
    
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('holding_id' => 'id'))->orderBy('count DESC');
    }
    public function getDecisions()
    {
        return $this->hasMany('app\models\HoldingDecision', array('holding_id' => 'id'))->orderBy('accepted ASC, created DESC');
    }
    public function getLicenses()
    {
        return $this->hasMany('app\models\HoldingLicense', array('holding_id' => 'id'));
    }
    
    /**
     * Общее число акций
     * @return integer
     */
    public function getSumStocks()
    {
        $sum = 0;
        foreach ($this->stocks as $stock) {
            $sum += $stock->count;
        }
        return $sum;
    }
    
    /**
     * Является ли гос. предприятием
     * @return boolean
     */
    public function isGosHolding()
    {
        foreach ($this->stocks as $stock) {
            if ($stock->isGos() && $stock->getPercents()>50) return true;
        }
        return false;
    }
    
    /**
     * Проверка, есть ли лицензия этого типа у холдинга
     * @param integer $licenseTypeId
     * @return boolean
     */
    public function isHaveLicense($licenseTypeId) {
        foreach ($this->licenses as $license) {
            if ($license->license_id === $licenseTypeId) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Удаление лицензии у холдинга по ID типа
     * @param integer $licenseTypeId
     */
    public function deleteLicense($licenseTypeId) {
        foreach ($this->licenses as $license) {
            if ($license->license_id === $licenseTypeId) {
                $license->delete();
                return;
            }
        }
    }
}
