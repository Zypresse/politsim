<?php

namespace app\models\politics\constitution;

use Yii,
    app\models\politics\Party,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\politics\elections\ElectoralDistrict,
    app\models\base\MyActiveRecord;

/**
 * Статья конституции
 *
 * @property integer $ownerType тип того к кому она привязана (государство/регион/агенство/пост) const из ConstitutionOwnerType
 * @property integer $ownerId ID Того к кому она привязана (государство/регион/агенство/пост)
 * @property integer $type тип статьи const из ConstitutionArticleType
 * @property integer $subType подтип статьи (например если статья про налог на продажу, то тут id прототипа ресурса)
 * @property string $value значение 
 * @property string $value2 дополнительное значение если понадобится в сложных статьях
 * @property string $value3 дополнительное значение если понадобится в очень сложных статьях
 * 
 * @property ConstitutionOwner $owner
 * 
 */
class ConstitutionArticle extends MyActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        $className = ConstitutionArticleType::getClassNameByType($row['type']);
        return new $className($row);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutionsArticles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ // не менять порядок строк! используется в наследниках
            [['ownerType', 'ownerId', 'type', 'value'], 'required'],
            [['ownerType', 'ownerId', 'type', 'subType'], 'integer', 'min' => 0],
            [['ownerType', 'ownerId', 'type', 'subType'], 'unique', 'targetAttribute' => ['ownerType', 'ownerId', 'type', 'subType'], 'message' => 'The combination of Owner Type, Owner ID, Type and Sub Type has already been taken.'],
            [['value', 'value2', 'value3'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ownerType' => Yii::t('app', 'Owner Type'),
            'ownerId' => Yii::t('app', 'Owner ID'),
            'type' => Yii::t('app', 'Type'),
            'subType' => Yii::t('app', 'Sub Type'),
            'value' => Yii::t('app', 'Value'),
            'value2' => Yii::t('app', 'Value2'),
            'value3' => Yii::t('app', 'Value3'),
        ];
    }

    public static function primaryKey()
    {
        return ['ownerType', 'ownerId', 'type', 'subType'];
    }
    
    public function getOwner()
    {
        return $this->hasOne(ConstitutionOwnerType::getClassNameByType($this->ownerType), ['id' => 'ownerId']);
    }
    
    public function getStateId()
    {
        return $this->owner->getTaxStateId();
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
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateAgency($attribute, $params)
    {
        $agencyInState = Agency::find()
                ->where(['id' => $this->$attribute, 'stateId' => $this->getStateId()])
                ->exists();
        if ($agencyInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'Agency not found in state'));
            return false;
        }
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateElectoralDistrict($attribute, $params)
    {
        $districtInState = ElectoralDistrict::find()
                ->where(['id' => $this->$attribute, 'stateId' => $this->getStateId()])
                ->exists();
        if ($districtInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'Electoral District not found in state'));
            return false;
        }
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateParty($attribute, $params)
    {
        $partyInState = Party::find()
                ->where(['id' => $this->$attribute, 'stateId' => $this->getStateId()])
                ->exists();
        if ($partyInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'Party not found in state'));
            return false;
        }
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateRegion($attribute, $params)
    {
        $regionInState = Region::find()
                ->where(['id' => $this->$attribute, 'stateId' => $this->getStateId()])
                ->exists();
        if ($regionInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'Region not found in state'));
            return false;
        }
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateCity($attribute, $params)
    {
        $cityInState = City::find()
                ->joinWith('region')
                ->where(['cities.id' => $this->$attribute, 'regions.stateId' => $this->getStateId()])
                ->exists();
        if ($cityInState) {
            return true;
        } else {
            $this->addError($attribute, Yii::t('app', 'City not found in state'));
            return false;
        }
    }

}
