<?php

namespace app\models;

use app\components\MyModel;

/**
 * «Пакет» акций. Таблица "stocks".
 *
 * @property integer $id
 * @property integer $holding_id ID холдинга акции которого имеются ввиду
 * @property integer $unnp ID налогоплательщика которому принадлежит
 * @property integer $count Число акций
 * 
 * @property \app\components\NalogPayer $master Владелец пакета
 * @holding Holding $holding АО, акции которого имеются ввиду
 * @holding Unnp $unnpModel UNNP владельца пакета
 */
class Stock extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['holding_id', 'unnp', 'count'], 'required'],
            [['holding_id', 'unnp', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'holding_id'  => 'ID холдинга, акции которого',
            'unnp'        => 'ID налогоплательщика которому принадлежит',
            'count'       => 'Число акций',
        ];
    }

    public function getMaster()
    {
        return $this->unnpModel->master;
    }

    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }

    public function getUnnpModel()
    {
        return $this->hasOne('app\models\Unnp', array('id' => 'unnp'));
    }

    /**
     * Процент этого пакета
     * @return float
     */
    public function getPercents()
    {
        return 100 * $this->count / $this->holding->getSumStocks();
    }

    /**
     * Возвращает рыночную стоимость акций (из капитализации компании)
     * @return float
     */
    public function getCost()
    {
        return $this->holding->capital * $this->getPercents() / 100;
    }

    /**
     * Принадлежит государству
     * @return boolean
     */
    public function isGos()
    {
        return !!$this->post_id;
    }

}
