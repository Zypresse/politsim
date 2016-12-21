<?php

namespace app\models\politics\constitution\articles\postsonly;

use Yii,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\elections\ElectoralDistrict,
    app\models\politics\constitution\articles\base\DropdownArticle;

/**
 * 
 */
class DestignationType extends DropdownArticle
{
    
    const BY_OTHER_POST = 1;
    const BY_PRECURSOR = 2;
    const BY_AGENCY_ELECTION = 3;
    const BY_STATE_ELECTION = 4;
    const BY_DISTRICT_ELECTION = 5;
    
    public static function getList(): array
    {
        return [
            static::BY_OTHER_POST => Yii::t('app', 'By other agency post'),
            static::BY_PRECURSOR => Yii::t('app', 'By precursor'),
            static::BY_AGENCY_ELECTION => Yii::t('app', 'By agency election'),
            static::BY_STATE_ELECTION => Yii::t('app', 'By state election'),
            static::BY_DISTRICT_ELECTION => Yii::t('app', 'By electoral district election'),
        ];
    }
    
    public function rules()
    {
        $rules = parent::rules();
        switch ((int)$this->value) {
            case static::BY_OTHER_POST:
                $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid Agency Post ID'];
                $rules[] = ['value2', 'validateAgencyPost'];
                break;
            case static::BY_AGENCY_ELECTION:
                $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => Agency::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid Agency ID'];
                $rules[] = ['value2', 'validateAgency'];
                break;
            case static::BY_DISTRICT_ELECTION:
                $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => ElectoralDistrict::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid Electoral District ID'];
                $rules[] = ['value2', 'validateElectoralDistrict'];
                break;
        }
        return $rules;
    }

}
