<?php

namespace app\models\politics\constitution\articles\statesonly;

use Yii,
    app\models\politics\constitution\articles\base\CheckboxArticle;

/**
 * 
 */
class Business extends CheckboxArticle
{
    
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value3'];
        $rules[] = [['value', 'value2'], 'boolean'];
        return $rules;
    }
    
    public function getName()
    {
        return Yii::t('app', 'Local companies').' — '.($this->value ? Yii::t('yii', 'Yes') : Yii::t('yii', 'No')).', '.
                Yii::t('app', 'Foreign companies').' — '.($this->value2 ? Yii::t('yii', 'Yes') : Yii::t('yii', 'No'));
    }

}
