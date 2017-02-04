<?php

namespace app\models\politics\constitution\articles\postsonly;

use Yii,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\politics\elections\ElectoralDistrict,
    app\models\politics\constitution\articles\base\DropdownArticle;

/**
 * 
 * @property ElectoralDistrict $district
 * @property Agency $agency
 * @property Region $region
 */
class DestignationType extends DropdownArticle
{
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    const BY_OTHER_POST = 1;
    const BY_PRECURSOR = 2;
    const BY_AGENCY_ELECTION = 3;
    const BY_STATE_ELECTION = 4;
    const BY_DISTRICT_ELECTION = 5;
    const BY_REGION_ELECTION = 6;
    const BY_CITY_ELECTION = 7;
    
    const SECOND_TOUR = 1;
    const NONE_OF_THE_ABOVE = 2;
    
    public static function getList(): array
    {
        return [
            static::BY_OTHER_POST => Yii::t('app', 'By other agency post'),
            static::BY_PRECURSOR => Yii::t('app', 'By precursor'),
            static::BY_AGENCY_ELECTION => Yii::t('app', 'By agency election'),
            static::BY_STATE_ELECTION => Yii::t('app', 'By state election'),
            static::BY_DISTRICT_ELECTION => Yii::t('app', 'By electoral district election'),
            static::BY_REGION_ELECTION => Yii::t('app', 'By region election'),
            static::BY_CITY_ELECTION => Yii::t('app', 'By city election'),
        ];
    }
    
    public static function getSettingsList(): array
    {
        return [
            static::SECOND_TOUR => Yii::t('app', 'Allows second tour'),
            static::NONE_OF_THE_ABOVE => Yii::t('app', 'Add variant «None of the above»'),
        ];
    }
    
    public static function getSelectedSettings($value)
    {
        if (is_array($value)) {
            $sum = 0;
            foreach ($value as $val) {
                $sum |= (int)$val;
            }
            $value = $sum;
        }
        $selected = [];
        foreach (static::getSettingsList() as $val => $name) {
            if ($value & $val) {
                $selected[$val] = $name;
            }
        }
        return $selected;
    }
    
    public function rules()
    {
        $type = (int)$this->value;
        $rules = parent::rules();
        unset($rules[3]);
        $rules[] = [['value3'], 'integer', 'min' => 0];
        switch ($type) {
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
            case static::BY_REGION_ELECTION:
                $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => Region::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid Region ID'];
                $rules[] = ['value2', 'validateRegion'];
                break;
            case static::BY_CITY_ELECTION:
                $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => City::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid City ID'];
                $rules[] = ['value2', 'validateCity'];
                break;
        }
        return $rules;
    }
    
    public function getDistrict()
    {
        return $this->hasOne(ElectoralDistrict::className(), ['id' => 'value2']);
    }
    
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'value2']);
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'value2']);
    }
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'value2']);
    }

}
