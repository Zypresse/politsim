<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Utr,
    app\models\State,
    app\models\Region,
    app\models\User,
    app\models\licenses\License,
    app\models\factories\Factory,
    app\models\factories\Line,
    app\models\HoldingDecision;

/**
 * Акционерное общество. Таблица "holdings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property integer $region_id
 * @property integer $director_id
 * @property integer $main_office_id
 * @property double $capital
 * @property double $balance
 * 
 * @property Stock[] $stocks Акции
 * @property License[] $licenses Лицензии
 * @property Factory[] $factories Фабрики
 * @property Line[] $lines
 * @property User $director
 * @property State $state
 * @property Region $region
 * @property HoldingDecision[] $decisions
 */
class Holding extends MyModel implements TaxPayer
{

    public function getUnnpType()
    {
        return Utr::TYPE_HOLDING;
    }
    
    public function getUnnp() {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->utr = ($u) ? $u->id : 0;
            $this->save();
        } 
        return $this->utr;
    }

    public function isGoverment($stateId)
    {
        return false;
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
            [['state_id', 'region_id', 'director_id', 'utr'], 'integer'],
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
            'director_id' => 'Director ID',
            'capital'   => 'Капитализация',
        ];
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }

    public function getDirector()
    {
        return $this->hasOne(User::className(), array('id' => 'director_id'));
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::className(), array('holding_id' => 'id'))->orderBy('count DESC');
    }
    
    public function getStocksHaved()
    {
        return $this->hasMany(Stock::className(), array('unnp' => 'unnp'));
    }

    public function getDecisions()
    {
        return $this->hasMany(HoldingDecision::className(), array('holding_id' => 'id'))->orderBy('accepted ASC, created DESC');
    }

    public function getLicenses()
    {
        return $this->hasMany(License::className(), array('holding_id' => 'id'));
    }
    
    public function getLicensesByState($stateID)
    {
        return License::find()->where(['holding_id' => $this->id,'state_id'=>$stateID])->all();
    }

    public function getFactories()
    {
        return $this->hasMany(Factory::className(), array('holding_id' => 'id'));
    }

    public function getLines()
    {
        return $this->hasMany(Line::className(), array('holding_id' => 'id'));
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
            $sumPercents = 0;
            foreach ($this->stocks as $stock) {
                if ($stock->isGos($stateId)) {
                    $sumPercents += $stock->getPercents();
                }
            }
            $this->_isGosHolding = $sumPercents > 50.0;
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

    public function changeBalance($delta)
    {
        $this->balance += $delta;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a($this->name, "load_page('holding-info',{'id':{$this->id}})");
    }

    public function getTaxStateId()
    {
        return $this->state_id;
    }

    public function isTaxedInState($stateId)
    {
        return ($this->state_id === (int)$stateId);
    }

    public function getUserControllerId()
    {
        return $this->director_id;
    }

    public function isUserController($userId)
    {
        if ($this->director_id === $userId) {
            return true;
        }
        
        foreach ($this->stocks as $stock) {
            if ($stock->master->isUserController($userId)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function calcCapital()
    {       
        $this->capital = 0.0;

        // пока цена на акции 1 монета
        $this->capital += 1* $this->getSumStocks();

        // стоимость зданий как стоимость их постройки + деньги на счету
        foreach ($this->factories as $factory) {
            $this->capital += $factory->size * $factory->proto->build_cost + $factory->getBalance();
        }

        $this->capital += $this->balance;
    }
        
    public function afterDelete()
    {
        foreach ($this->stocks as $stock) {
            $stock->delete();
        }
        
        foreach ($this->licenses as $license) {
            $license->delete();
        }
        
        foreach ($this->decisions as $decision) {
            $decision->delete();
        }
        
        return parent::afterDelete();
    }

}
