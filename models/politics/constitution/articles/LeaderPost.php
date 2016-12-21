<?php

namespace app\models\politics\constitution\articles;

use Yii,
    app\models\politics\constitution\articles\base\UnsignedIntegerArticle,
    app\models\politics\AgencyPost;

/**
 * 
 */
class LeaderPost extends UnsignedIntegerArticle
{
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['value'], 'exist', 'skipOnError' => false, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['value' => 'id'], 'message' => 'Value must be valid Agency Post ID'];
        $rules[] = ['value', 'validateAgencyPost'];
        return $rules;
    }
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateAgencyPost($attribute, $params)
    {
        $postInState = AgencyPost::find()
                ->where(['id' => $this->$attribute, 'stateId' => $this->getStateId()])
                ->exists();
        if ($postInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'Agency post not found in state'));
            return false;
        }
    }
}
