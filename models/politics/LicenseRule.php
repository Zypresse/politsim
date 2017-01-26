<?php

namespace app\models\politics;

use Yii,
    app\models\base\MyActiveRecord,
    app\models\politics\State,
    app\models\economics\LicenseProto;

/**
 * Правила по выдаче лицензий
 *
 * @property integer $protoId
 * @property integer $stateId
 * @property integer $whichCompaniesAllowed
 * @property boolean $isNeedConfirmation
 * @property double $priceForResidents
 * @property double $priceForNonresidents
 */
class LicenseRule extends MyActiveRecord
{
    
    /**
     * госкомпании (> 50% государства)
     */
    const ALLOWED_GOVERMENT = 1;
    
    /**
     * компании с гос. участием
     */
    const ALLOWED_HALF_GOVERMENT = 2;
    
    /**
     * компании-резиденты
     */
    const ALLOWED_LOCAL = 3;
    
    /**
     * компании-нерезиденты
     */
    const ALLOWED_ALL = 4;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licensesRules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'stateId', 'whichCompaniesAllowed'], 'required'],
            [['protoId'], 'integer'],
            [['stateId', 'whichCompaniesAllowed'], 'integer', 'min' => 0],
            [['priceForResidents', 'priceForNonresidents'], 'number', 'min' => 0],
            [['isNeedConfirmation'], 'boolean'],
            [['protoId', 'stateId'], 'unique', 'targetAttribute' => ['protoId', 'stateId'], 'message' => 'The combination of Proto ID and State ID has already been taken.'],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'protoId' => Yii::t('app', 'Proto ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'whichCompaniesAllowed' => Yii::t('app', 'Which Companies Allowed'),
            'isNeedConfirmation' => Yii::t('app', 'Is Need Confirmation'),
            'priceForResidents' => Yii::t('app', 'Price For Residents'),
            'priceForNonresidents' => Yii::t('app', 'Price For Nonresidents'),
        ];
    }
    
    public function getProto()
    {
        return LicenseProto::findOne($this->protoId);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    
}
