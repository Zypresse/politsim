<?php

namespace app\models\factories;

use Yii,
    app\components\MyModel,
    app\models\factories\Factory,
    app\models\factories\FactoryAuctionBet,
    app\models\Unnp,
    app\models\Dealing;

/**
 * This is the model class for table "factory_auctions".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $date_end
 * @property double $start_price
 * @property double $current_price
 * @property double $end_price
 * @property integer $winner_unnp
 * @property integer $closed
 * 
 * @property Factory $factory
 * @property string $factoryName
 * @property string $holdingName
 * @property string $regionName
 * @property \app\components\NalogPayer $winner
 * @property FactoryAuctionBet[] $bets
 * @property FactoryAuctionBet $lastBet
 */
class FactoryAuction extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_auctions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'date_end', 'start_price'], 'required'],
            [['factory_id', 'date_end', 'winner_unnp', 'closed'], 'integer'],
            [['start_price', 'end_price', 'current_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'date_end' => 'Date End',
            'start_price' => 'Start Price',
            'current_price' => 'Current Price',
            'end_price' => 'End Price',
            'winner_unnp' => 'Winner Unnp',
        ];
    }    
    
    public function getFactory()
    {
        return $this->hasOne(Factory::className(), array('id' => 'factory_id'));
    }
    
    public function getBets()
    {
        return $this->hasMany(FactoryAuctionBet::className(), array('auction_id' => 'id'))->orderBy('time DESC');
    }
    
    public function getLastBet()
    {
        return $this->hasOne(FactoryAuctionBet::className(), array('auction_id' => 'id'))->orderBy('time DESC');
    }
    
    public function getWinner()
    {
        return Unnp::findByPk($this->winner_unnp)->master;
    }
    
    public function getFactoryName()
    {
        return $this->factory->name;
    }
    
    public function getHoldingName()
    {
        return $this->factory->holding->name;
    }
    
    public function getRegionName()
    {
        return $this->factory->region->name;
    }
    
    public function end() {
        if ($this->lastBet) {
            $this->winner_unnp = $this->lastBet->holding->unnp;
            $this->closed = 1;
            $this->save();

            $dealing = new Dealing([
                'proto_id' => 1,
                'from_unnp' => $this->factory->holding->unnp,
                'to_unnp' => $this->lastBet->holding->unnp,
                'sum' => -1*$this->lastBet->bet,
                'items' => json_encode([
                    [
                        'type' => 'factory',
                        'factory_id' => $this->factory->id
                    ]
                ])
            ]);
            $dealing->accept();
            
            Yii::$app->db->createCommand()
                ->delete(FactoryAuctionBet::tableName(),
                    "auction_id = :aid",
                    [":aid" => $this->id])
                ->execute();
        }
    }

}