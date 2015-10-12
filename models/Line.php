<?php

namespace app\models;

use Yii,
    app\components\NalogPayer;

/**
 * Трубопроводы, ЛЭП и т.п. Таблица "lines".
 *
 * @property integer $id
 * @property integer $region1_id
 * @property integer $region2_id
 * @property integer $resurse
 * @property integer $holding_id
 */
class Line extends NalogPayer \yii\db\ActiveRecord
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
            [['region1_id', 'region2_id', 'resurse', 'holding_id'], 'required'],
            [['region1_id', 'region2_id', 'resurse', 'holding_id'], 'integer']
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
            'resurse' => 'Resurse',
            'holding_id' => 'Holding ID',
        ];
    }
}