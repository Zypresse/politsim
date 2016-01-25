<?php

namespace app\models\resurses;

use app\components\MyModel,
    app\models\resurses\Resurse,
    app\models\Holding,
    app\models\State;

/**
 * Ценники на ресурсы. Таблица "resurses_costs".
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
        return 'resurses_costs';
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
            return "Налогоплательщики {$this->state->getHtmlShortName()}";
        }
        
        if ($this->holding_id) {
            return "Предприятия {$this->holding->getHtmlName()}";
        }
        
        return "Любые покупатели";
    }
    
    /**
     * Поиск предложений еды подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableFood($stateId, $count)
    {
        return static::getBuyableResurses([
            9, // Зерно
            10, // Фруктовощи
            11, // Рыба
            12, // Мясо
            25 // Готовая еда
        ], $stateId, $count);        
    }
    
    /**
     * Поиск предложений одежды подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableDress($stateId, $count)
    {
        return static::getBuyableResurses([
            35 // одежда и обувь
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений электричества подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableElecticity($stateId, $count)
    {
        return static::getBuyableResurses([
            16 // электричество
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений алкоголя подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableAlcohol($stateId, $count)
    {
        return static::getBuyableResurses([
            36 // алкоголь
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений мебели подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableFurniture($stateId, $count)
    {
        return static::getBuyableResurses([
            37 // мебель
        ], $stateId, $count);        
    }    
    
    /**
     * 
     * @param int[] $resProtoIds
     * @param int $stateId
     * @param double $count
     * 
     * @return ResurseCost[] 
     */
    public static function getBuyableResurses($resProtoIds, $stateId, $count)
    {
        $costs = static::find()
                    ->join('LEFT JOIN', Resurse::tableName(), Resurse::tableName().'.id = '.static::tableName().'.resurse_id')
                    ->where(['IN', Resurse::tableName().'.proto_id', $resProtoIds])
                    ->andWhere(['>',Resurse::tableName().'.count',0])
                    ->andWhere(['holding_id'=>null])
                    ->andWhere(['or',['state_id'=>null],['state_id'=>$stateId]])
                    ->with('resurse')
                    ->orderBy('cost ASC, '.Resurse::tableName().'.quality DESC')
                    ->groupBy(Resurse::tableName().'.place_id')
                    ->all();
        
        $toBuyLeft = $count;
        $result = [];
        foreach ($costs as $cost) {
            /* @var $cost self */
            $toBuyLeft -= $cost->resurse->count;
            $result[] = $cost;
            if ($toBuyLeft <= 0) {
                break;
            }
        }
        
        return $result;
    }
}
