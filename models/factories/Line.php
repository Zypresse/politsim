<?php

namespace app\models\factories;

use app\components\NalogPayer,
    app\models\Holding,
    app\models\Region,
    app\models\resurses\proto\ResurseProto;

/**
 * Трубопроводы, ЛЭП и т.п. Таблица "lines".
 *
 * @property integer $id
 * @property integer $region1_id
 * @property integer $region2_id
 * @property integer $resurse_proto_id
 * @property integer $holding_id
 * 
 * @property Region $region1
 * @property Region $region2
 * @property Holding $holding
 * @property ResurseProto $resurseProto
 */
class Line extends NalogPayer
{

    protected function getUnnpType()
    {
        return Unnp::TYPE_LINE;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lines';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region1_id', 'region2_id', 'resurse_proto_id', 'holding_id'], 'required'],
            [['region1_id', 'region2_id', 'resurse_proto_id', 'holding_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region1_id' => 'Region1 ID',
            'region2_id' => 'Region2 ID',
            'resurse_proto_id' => 'Resurse Proto ID',
            'holding_id' => 'Holding ID',
        ];
    }
        
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }
        
    public function getRegion1()
    {
        return $this->hasOne(Region::className(), array('id' => 'region1_id'));
    }
        
    public function getRegion2()
    {
        return $this->hasOne(Region::className(), array('id' => 'region2_id'));
    }
        
    public function getResurseProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'resurse_proto_id'));
    }

}