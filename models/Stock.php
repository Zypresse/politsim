<?php

namespace app\models;

use app\components\MyModel;

/**
 * «Пакет» акций. Таблица "stocks".
 *
 * @property integer $id
 * @property integer $holding_id ID холдинга акции которого имеются ввиду
 * @property integer $user_id ID юзера, которому принадлежит
 * @property integer $post_id ID поста, которому принадлежит
 * @property integer $hholding_id ID холдинга, которому принадлежит
 * @property integer $count Число акций
 * 
 * @property mixed $master Владелец пакета
 * @holding \app\models\Holding $holding АО, акции которого имеются ввиду
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
            [['holding_id', 'count'], 'required'],
            [['holding_id', 'user_id', 'post_id', 'hholding_id', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'holding_id' => 'ID холдинга, акции которого',
            'user_id' => 'ID юзера-владельца',
            'post_id' => 'ID министра-владельца',
            'hholding_id' => 'ID холдинга-владельца',
            'count' => 'Число акций',
        ];
    }
    
    public function getMaster()
    {
        if ($this->user_id) {
            return $this->hasOne('app\models\User', array('id' => 'user_id'));
        } elseif ($this->post_id) {
            return $this->hasOne('app\models\Post', array('id' => 'post_id'));
        } elseif ($this->hholding_id) {
            return $this->hasOne('app\models\Holding', array('id' => 'hholding_id'));
        } else {
            return NULL;
        }
    }
    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
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
            
}
