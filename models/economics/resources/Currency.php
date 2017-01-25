<?php

namespace app\models\economics\resources;

use Yii,
    app\models\economics\resources\base\SubtypesResourceProto;

/**
 * Валюта
 *
 * @property integer $id
 * @property string $emissionerId
 * @property string $name
 * @property string $nameShort
 * @property double $inflation
 * @property double $exchangeRate
 * @property double $exchangeRateReal
 */
final class Currency extends SubtypesResourceProto
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emissionerId', 'name', 'nameShort'], 'required'],
            [['emissionerId'], 'integer', 'min' => 0],
            [['inflation', 'exchangeRate', 'exchangeRateReal'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['emissionerId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['emissionerId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'emissionerId' => Yii::t('app', 'Emissioner ID'),
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'inflation' => Yii::t('app', 'Inflation'),
            'exchangeRate' => Yii::t('app', 'Exchange Rate'),
            'exchangeRateReal' => Yii::t('app', 'Exchange Rate Real'),
        ];
    }
    
}
