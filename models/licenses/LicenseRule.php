<?php

namespace app\models\licenses;

use app\components\MyModel,
    app\models\State,
    app\models\licenses\proto\LicenseProto as Proto;

/**
 * Пункты «экономической конституции» государств. Партия "licenses_rules".
 *
 * @property integer $id
 * @property integer $state_id ID государства
 * @property integer $proto_id ID типа лицензии
 * @property integer $is_only_goverment Гос. монополия на л.
 * @property integer $is_need_confirm Для л. требуется подтверждение министра
 * @property double $cost Цена л.
 * @property integer $is_need_confirm_noncitizens Для иностранных компаний л. требуется подтверждение министра
 * @property double $cost_noncitizens Цена л. для иностранных компаний
 * 
 * @property Proto $proto Тип лицензии
 * @property State $state Государство
 */
class LicenseRule extends MyModel {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licenses_rules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'proto_id'], 'required'],
            [['state_id', 'proto_id', 'is_only_goverment', 'is_need_confirm', 'is_need_confirm_noncitizens'], 'integer'],
            [['cost', 'cost_noncitizens'], 'number']
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
            'proto_id' => 'License ID',
            'is_only_goverment' => 'Is Only Goverment',
            'is_need_confirm' => 'Is Need Confirm',
            'cost' => 'Cost',
        ];
    }

    public function getProto()
    {
        return $this->hasOne(Proto::className(), array('id' => 'proto_id'));
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }

}
