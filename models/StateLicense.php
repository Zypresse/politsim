<?php

namespace app\models;

use app\components\MyModel;

/**
 * Пункты «экономической конституции» государств. Партия "state_licenses".
 *
 * @property integer $id
 * @property integer $state_id ID государства
 * @property integer $license_id ID типа лицензии
 * @property integer $is_only_goverment Гос. монополия на л.
 * @property integer $is_need_confirm Для л. требуется подтверждение министра
 * @property double $cost Цена л.
 * @property integer $is_need_confirm_noncitizens Для иностранных компаний л. требуется подтверждение министра
 * @property double $cost_noncitizens Цена л. для иностранных компаний
 * 
 * @property \app\models\HoldingLicenseType $type Тип лицензии
 * @property \app\models\State $state Государство
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
            [['state_id', 'license_id', 'is_only_goverment', 'is_need_confirm', 'is_need_confirm_noncitizens'], 'integer'],
            [['cost', 'cost_noncitizens'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'state_id'          => 'State ID',
            'license_id'        => 'License ID',
            'is_only_goverment' => 'Is Only Goverment',
            'is_need_confirm'   => 'Is Need Confirm',
            'cost'              => 'Cost',
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
