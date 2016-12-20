<?php

namespace app\models\politics\constitution\articles;

use app\models\politics\constitution\ConstitutionArticle,
    app\models\politics\AgencyPost;

/**
 * 
 */
class LeaderPost extends ConstitutionArticle
{
    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value2', 'value3'];
        $rules[] = [['value'], 'integer', 'min' => 0];
        $rules[] = [['value'], 'exist', 'skipOnError' => false, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['value' => 'id'], 'message' => 'Value must be valid Agency Post ID'];
        return $rules;
    }
}
