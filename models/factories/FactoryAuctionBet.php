<?php

namespace app\models\factories;

use app\components\MyModel,
    app\models\factories\FactoryAuction,
    app\models\Holding;

/**
 * This is the model class for table "factories_auction_bets".
 *
 * @property integer $id
 * @property integer $auction_id
 * @property integer $holding_id
 * @property double $bet
 * @property integer $time
 *
 * @property FactoryAuction $auction
 * @property Holding $holding
 */
class FactoryAuctionBet extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_auction_bets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auction_id', 'holding_id', 'bet', 'time'], 'required'],
            [['auction_id', 'holding_id', 'time'], 'integer'],
            [['bet'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auction_id' => 'Auction ID',
            'holding_id' => 'Holding ID',
            'bet' => 'Bet',
            'time' => 'Time',
        ];
    }

    public function getAuction()
    {
        return $this->hasOne(FactoryAuction::className(), array('id' => 'auction_id'));
    }

    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->auction->current_price = $this->bet;
            $this->auction->save();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}