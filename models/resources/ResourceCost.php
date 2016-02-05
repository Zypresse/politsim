<?php

namespace app\models\resources;

use app\components\MyModel,
    app\models\resources\Resource,
    app\models\Holding,
    app\models\State;

/**
 * Ценники на ресурсы. Таблица "resources_costs".
 *
 * @property integer $id
 * @property integer $resource_id (не путать с resource_proto_id)
 * @property double $cost Цена за единицу
 * @property integer $holding_id если установленно то продаётся только фабрикам этого холдинга
 * @property integer $state_id если установленно то продаётся только налогоплательщикам этой страны
 * 
 * @property Resource $resource
 * @property State $state
 * @property Holding $holding
 */
class ResourceCost extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources_costs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_id', 'cost'], 'required'],
            [['resource_id', 'holding_id', 'state_id'], 'integer'],
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
            'resource_id' => 'Resource ID',
            'cost' => 'Cost',
            'holding_id' => 'Holding ID',
            'state_id' => 'State ID',
        ];
    }
    
    public function getResource()
    {
        return $this->hasOne(Resource::className(), array('id' => 'resource_id'));
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
        if ($this->state) {
            return "Налогоплательщики {$this->state->getHtmlShortName()}";
        }
        
        if ($this->holding) {
            return "Предприятия {$this->holding->getHtmlName()}";
        }
        
        return "Любые покупатели";
    }
    
    /**
     * Поиск предложений еды подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResourceCost[] 
     */
    public static function getBuyableFood($stateId, $count)
    {
        return static::getBuyableResources([
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
     * @return ResourceCost[] 
     */
    public static function getBuyableDress($stateId, $count)
    {
        return static::getBuyableResources([
            35 // одежда и обувь
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений электричества подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResourceCost[] 
     */
    public static function getBuyableElecticity($stateId, $count)
    {
        return static::getBuyableResources([
            16 // электричество
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений алкоголя подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResourceCost[] 
     */
    public static function getBuyableAlcohol($stateId, $count)
    {
        return static::getBuyableResources([
            36 // алкоголь
        ], $stateId, $count);        
    }    
    
    /**
     * Поиск предложений мебели подходящих по стране и ограниченных числом count
     * @param int $stateId
     * @param double $count
     * 
     * @return ResourceCost[] 
     */
    public static function getBuyableFurniture($stateId, $count)
    {
        return static::getBuyableResources([
            37 // мебель
        ], $stateId, $count);        
    }    
    
    /**
     * 
     * @param int[] $resProtoIds
     * @param int $stateId
     * @param double $count
     * 
     * @return ResourceCost[] 
     */
    public static function getBuyableResources($resProtoIds, $stateId, $count)
    {
        $costs = static::find()
                    ->join('LEFT JOIN', Resource::tableName(), Resource::tableName().'.id = '.static::tableName().'.resource_id')
                    ->where(['IN', Resource::tableName().'.proto_id', $resProtoIds])
                    ->andWhere(['>',Resource::tableName().'.count',0])
                    ->andWhere(['holding_id'=>null])
                    ->andWhere(['or',['state_id'=>null],['state_id'=>$stateId]])
                    ->with('resource')
                    ->orderBy('cost ASC, '.Resource::tableName().'.quality DESC')
                    ->groupBy(Resource::tableName().'.place_id')
                    ->all();
        
        $toBuyLeft = $count;
        $result = [];
        foreach ($costs as $cost) {
            /* @var $cost self */
            $toBuyLeft -= $cost->resource->count;
            $result[] = $cost;
            if ($toBuyLeft <= 0) {
                break;
            }
        }
        
        return $result;
    }
}
