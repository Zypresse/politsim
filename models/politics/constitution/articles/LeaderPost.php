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
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'value']);
    }
    
}

