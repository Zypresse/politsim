<?php

namespace app\models;

use app\components\NalogPayer,
    app\models\Unnp;

/**
 * Акционерное общество. Таблица "holdings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property integer $region_id
 * @property double $capital
 * @property double $balance
 * 
 * @property Stock[] $stocks Акции
 * @property licenses\License[] $licenses Лицензии
 * @property factories\Factory[] $factories Фабрики
 */
class Holding extends NalogPayer
{

    protected function getUnnpType()
    {
        return Unnp::TYPE_HOLDING;
    }

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
            [['capital', 'balance'], 'number'],
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
            'id'        => 'ID',
            'name'      => 'Name',
            'state_id'  => 'State ID',
            'region_id' => 'Region ID',
            'capital'   => 'Капитализация',
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
    
    public function getStocksHaved()
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }

    public function getDecisions()
    {
        return $this->hasMany('app\models\HoldingDecision', array('holding_id' => 'id'))->orderBy('accepted ASC, created DESC');
    }

    public function getLicenses()
    {
        return $this->hasMany('app\models\licenses\License', array('holding_id' => 'id'));
    }
    
    public function getLicensesByState($stateID)
    {
        return License::find()->where(['holding_id' => $this->id,'state_id'=>$stateID])->all();
    }

    public function getFactories()
    {
        return $this->hasMany('app\models\factories\Factory', array('holding_id' => 'id'));
    }

    private $_sumStocks = null;
    
    /**
     * Общее число акций
     * @return integer
     */
    public function getSumStocks()
    {
        if (is_null($this->_sumStocks)) {
            $this->_sumStocks = 0;
            foreach ($this->stocks as $stock) {
                $this->_sumStocks += $stock->count;
            }
        }
        return $this->_sumStocks;
    }

    private $_isGosHolding = null;

    /**
     * Является ли гос. предприятием
     * 
     * @param integer $stateId
     * @return boolean
     */
    public function isGosHolding($stateId)
    {
        if (is_null($this->_isGosHolding)) {
            $this->_isGosHolding = false;
            foreach ($this->stocks as $stock) {
                if ($stock->isGos($stateId) && $stock->getPercents() > 50) {
                    $this->_isGosHolding = true;
                    break;
                }
            }
        }
        return $this->_isGosHolding;
    }

    /**
     * Проверка, есть ли лицензия этого типа у холдинга
     * @param integer $stateId
     * @param integer $licenseTypeId
     * @return boolean
     */
    public function isHaveLicense($stateId,$licenseTypeId)
    {
        foreach ($this->licenses as $license) {
            if ($license->proto_id === $licenseTypeId && $license->state_id === $stateId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Удаление лицензии у холдинга по ID типа
     * @param integer $licenseTypeId
     */
    public function deleteLicense($licenseTypeId)
    {
        foreach ($this->licenses as $license) {
            if ($license->proto_id === $licenseTypeId) {
                $license->delete();
                return;
            }
        }
    }

}
