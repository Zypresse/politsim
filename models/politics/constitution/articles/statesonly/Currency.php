<?php

namespace app\models\politics\constitution\articles\statesonly;

use Yii,
    yii\helpers\ArrayHelper,
    app\models\politics\constitution\articles\base\DropdownArticle,
    app\models\economics\resources\Currency as CurrencyModel;

/**
 * Официальная валюта государства
 * 
 * @property CurrencyModel $proto
 */
class Currency extends DropdownArticle
{
    
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['value'], 'exist', 'skipOnError' => false, 'targetClass' => CurrencyModel::className(), 'targetAttribute' => ['value' => 'id']];
        return $rules;
    }

    public static function getList(): array
    {
        return ArrayHelper::map(CurrencyModel::findAll(), 'id', 'name');
    }
    
    public function getProto()
    {
        return $this->hasOne(CurrencyModel::className(), ['id' => 'value']);
    }
    
    public function getName()
    {
        return $this->proto ? $this->proto->name : Yii::t('yii', '(not set)');
    }

}
