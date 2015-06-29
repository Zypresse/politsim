<?php

namespace app\models;

use app\components\MyModel;

/**
 * Привязка холдингов к лицензиям. Таблица "holding_licenses".
 *
 * @property integer $id
 * @property integer $holding_id
 * @property integer $license_id
 * @property integer $state_id
 * 
 * @property \app\models\Holding $holding Акционерное общество
 * @property \app\models\HoldingLicenseType $type Тип лицензии
 * @property State $state Государство, выдавшее лицензию
 */
class HoldingLicense extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holding_licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['holding_id', 'license_id', 'state_id'], 'required'],
            [['holding_id', 'license_id', 'state_id'], 'integer'],
            [['holding_id', 'license_id', 'state_id'], 'unique', 'targetAttribute' => ['holding_id', 'license_id', 'state_id'], 'message' => 'The combination of Holding ID, License ID and State ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'holding_id' => 'Holding ID',
            'license_id' => 'License ID',
            'state_id' => 'State ID',
        ];
    }

    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
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
