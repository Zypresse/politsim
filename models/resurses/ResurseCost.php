<?php

namespace app\models\resurses;

use app\components\MyModel,
    app\models\resurses\Resurse,
    app\models\Holding,
    app\models\State;

/**
 * Ценники на ресурсы. Таблица "resurse_costs".
 *
 * @property integer $id
 * @property integer $resurse_id (не путать с resurse_proto_id)
 * @property double $cost Цена за единицу
 * @property integer $holding_id если установленно то продаётся только фабрикам этого холдинга
 * @property integer $state_id если установленно то продаётся только налогоплательщикам этой страны
 * 
 * @property Resurse $resurse
 * @property State $state
 * @property Holding $holding
 */
class ResurseCost extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurse_costs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resurse_id', 'cost'], 'required'],
            [['resurse_id', 'holding_id', 'state_id'], 'integer'],
            [['cost'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resurse_id' => 'Resurse ID',
            'cost' => 'Cost',
            'holding_id' => 'Holding ID',
            'state_id' => 'State ID',
        ];
    }
    
    public function getResurse()
    {
        return $this->hasOne(Resurse::className(), array('id' => 'resurse_id'));
    }
    
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
    
    public function getHtmlType()
    {
        if ($this->state_id) {
            return "(для предприятий {$this->state->short_name})";
        }
        
        if ($this->holding_id) {
            return "(для предприятий ".$this->holding->getHtmlName().")";
        }
        
        return "";
    }
}
