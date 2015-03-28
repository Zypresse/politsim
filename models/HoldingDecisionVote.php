<?php

namespace app\models;

use app\components\MyModel;

/**
 * Голос по решениюв управлении АО. Таблица "holding_decisions_votes".
 *
 * @property integer $id
 * @property integer $decision_id
 * @property integer $variant
 * @property integer $stock_id
 */
class HoldingDecisionVote extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holding_decisions_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['decision_id', 'variant', 'stock_id'], 'required'],
            [['decision_id', 'variant', 'stock_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'decision_id' => 'Decision ID',
            'variant'     => 'Variant',
            'stock_id'    => 'Stock ID',
        ];
    }

    public function getDecision()
    {
        return $this->hasOne('app\models\HoldingDecision', array('id' => 'decision_id'));
    }

    public function getStock()
    {
        return $this->hasOne('app\models\Stock', array('id' => 'stock_id'));
    }

}
