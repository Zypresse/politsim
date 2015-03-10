<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "state_licenses".
 *
 * @property integer $id
 * @property integer $state_id
 * @property integer $license_id
 * @property integer $is_only_goverment
 * @property integer $is_need_confirm
 * @property double $cost
 */
class StateLicense extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state_licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'license_id'], 'required'],
            [['state_id', 'license_id', 'is_only_goverment', 'is_need_confirm'], 'integer'],
            [['cost'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_id' => 'State ID',
            'license_id' => 'License ID',
            'is_only_goverment' => 'Is Only Goverment',
            'is_need_confirm' => 'Is Need Confirm',
            'cost' => 'Cost',
        ];
    }
    public function getType()
    {
        return $this->hasOne('app\models\HoldingLicenseType', array('id' => 'license_id'));
    }
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
}