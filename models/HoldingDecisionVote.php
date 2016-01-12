<?php

namespace app\models;

use app\components\MyModel,
    app\models\HoldingDecision as Decision,
    app\models\Stock;

/**
 * Голос по решениюв управлении АО. Таблица "holdings_decisions_votes".
 *
 * @property integer $id
 * @property integer $decision_id
 * @property integer $variant
 * @property integer $stock_id
 * 
 * @property Decision $decision
 * @property Stock $stock
 */
class HoldingDecisionVote extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holdings_decisions_votes';
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
        return $this->hasOne(Decision::className(), array('id' => 'decision_id'));
    }

    public function getStock()
    {
        return $this->hasOne(Stock::className(), array('id' => 'stock_id'));
    }

}
