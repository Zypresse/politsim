<?php

namespace app\models\factories;

use app\components\MyModel,
    app\models\factories\Factory,
    app\models\Unnp;

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
 * 
 * @property Factory $factory
 * @property string $factoryName
 * @property string $holdingName
 * @property string $regionName
 * @property \app\components\NalogPayer $winner
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
            [['factory_id', 'date_end', 'winner_unnp'], 'integer'],
            [['start_price', 'end_price', 'current_price'], 'number'],
            [['factory_id'], 'unique']
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

}