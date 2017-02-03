<?php

namespace app\models\economics\units;

use Yii,
    app\models\economics\Utr,
    app\models\economics\TaxPayerModel,
    app\models\economics\resources\Currency,
    app\models\population\Pop,
    app\models\population\PopClass,
    app\models\base\MyActiveRecord;

/**
 * This is the model class for table "vacancies".
 *
 * @property integer $id
 * @property integer $objectId (UTR)
 * @property integer $popClassId
 * @property integer $currencyId
 * @property double $wage
 * @property integer $countAll
 * @property integer $countFree
 * 
 * @property TaxPayerModel $object
 * @property PopClass $popClass
 * @property Currency $currency
 * @property Pop[] $pops
 * 
 */
class Vacancy extends MyActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vacancies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objectId', 'popClassId', 'currencyId', 'wage', 'countAll'], 'required'],
            [['objectId', 'popClassId', 'currencyId', 'countFree'], 'integer', 'min' => 0],
            [['countAll'], 'integer', 'min' => 1],
            [['wage'], 'number', 'min' => 0],
            [['objectId', 'popClassId'], 'unique', 'targetAttribute' => ['objectId', 'popClassId'], 'message' => Yii::t('app', 'Vacancy for this population class allready exist')],
            [['currencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currencyId' => 'id']],
            [['objectId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['objectId' => 'id']],
            [['popClassId'], 'validatePopClassId'],
            [['wage'], 'validateWage'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'objectId' => Yii::t('app', 'Object ID'),
            'popClassId' => Yii::t('app', 'Pop Class ID'),
            'currencyId' => Yii::t('app', 'Currency ID'),
            'wage' => Yii::t('app', 'Wage'),
            'countAll' => Yii::t('app', 'Count All'),
            'countFree' => Yii::t('app', 'Count Free'),
        ];
    }
    
    public function validatePopClassId($attribute, $params)
    {
        if (!PopClass::exist($this->$attribute)) {
            $this->addError($attribute, Yii::t('app', 'Invalid population class'));
            return false;
        }
        return true;
    }
    
    public function validateWage($attribute, $params)
    {
        
        // TODO проверка минимальной зарплаты в государстве
        
        if ($this->$attribute <= 0) {
            $this->addError($attribute, Yii::t('app', 'Minimal wage in this state is {0}', [0]));
            return false;
        }
        return true;
    }
    
    public function beforeSave($insert)
    {
        $this->calcCountFree();
        return parent::beforeSave($insert);
    }
    
    public function calcCountFree()
    {
        // TODO проверка занятых вакансий
        $this->countFree = $this->countAll;
    }
    
    public function getObjectUtr()
    {
        return $this->hasOne(Utr::className(), ['id' => 'objectId']);
    }
    
    public function getObject()
    {
        return $this->objectUtr->object;
    }
    
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currencyId']);
    }
    
    public function getPopClass()
    {
        return PopClass::findOne($this->popClassId);
    }
    
    public function getPops()
    {
        return $this->hasMany(Pop::className(), ['id' => 'popId'])
                ->viaTable('vacanciesToPops', ['vacancyId' => 'id']);
    }
        
}
