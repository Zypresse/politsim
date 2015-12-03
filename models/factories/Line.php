<?php

namespace app\models\factories;

use app\components\TaxPayer,
    app\models\objects\UnmovableObject,
    app\models\Holding,
    app\models\Region,
    app\models\factories\proto\LineProto;

/**
 * Трубопроводы, ЛЭП и т.п. Таблица "lines".
 *
 * @property integer $id
 * @property integer $region1_id
 * @property integer $region2_id
 * @property integer $proto_id
 * @property integer $holding_id
 * 
 * @property Region $region1
 * @property Region $region2
 * @property Holding $holding
 * @property proto\LineProto $proto
 */
class Line extends UnmovableObject implements TaxPayer
{
    
    private $_unnp;
    public function getUnnp() {
        if (is_null($this->_unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->_unnp = ($u) ? $u->id : 0;
        } 
        return $this->_unnp;
    }

    public function getUnnpType()
    {
        return Unnp::TYPE_LINE;
    }
    
    public function isGoverment($stateId)
    {
        return false;
    }
    
    public static function getPrice($distance)
    {
        return $distance * $this->proto->build_cost;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lines';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region1_id', 'region2_id', 'proto_id', 'holding_id'], 'required'],
            [['region1_id', 'region2_id', 'proto_id', 'holding_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region1_id' => 'Region1 ID',
            'region2_id' => 'Region2 ID',
            'resurse_proto_id' => 'Resurse Proto ID',
            'holding_id' => 'Holding ID',
        ];
    }
        
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }
        
    public function getRegion1()
    {
        return $this->hasOne(Region::className(), array('id' => 'region1_id'));
    }
        
    public function getRegion2()
    {
        return $this->hasOne(Region::className(), array('id' => 'region2_id'));
    }
        
    public function getProto()
    {
        return $this->hasOne(LineProto::className(), array('id' => 'proto_id'));
    }

    public function changeBalance($delta)
    {
        
    }

    public function getBalance()
    {
        return 0;
    }

    public function getHtmlName()
    {
        return $this->proto->name;
    }

    public function getTaxStateId()
    {
        return $this->region1 ? $this->region1->state_id : 0;
    }

    public function isTaxedInState($stateId)
    {
        if (is_null($this->region1)) {
            return false;
        }
        
        return $this->region1->state_id === (int)$stateId;
    }

    public function getUserControllerId()
    {
        return 0;
    }

    public function isUserController($userId)
    {
        return false;
    }

}