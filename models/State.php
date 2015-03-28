<?php

namespace app\models;

use app\components\MyModel;
use app\models\GovermentFieldType;
use app\models\GovermentFieldValue;

/**
 * Государство. Таблица "states".
 *
 * @property integer $id
 * @property string $name Название
 * @property string $short_name Короткое название (2-3 буквы)
 * @property string $capital Код региона-столицы
 * @property string $color Цвет страны на карте (с #)
 * @property integer $legislature ID организации законодательной власти
 * @property integer $executive ID организации исполнительной власти
 * @property integer $state_structure ID «структуры» государства
 * @property integer $goverment_form ID формы правления государства
 * @property integer $group_id ID группы страны в вк
 * @property integer $population Население
 * @property integer $sum_star Сумма известности жителей
 * @property integer $allow_register_parties Разрешено ли регистрировать партии
 * @property integer $leader_can_drop_legislature Может ли лидер распустить парламент
 * @property integer $allow_register_holdings Разрешено ли регистировать АО
 * @property integer $register_parties_cost Стоимость регистрации партии
 * 
 * @property \app\models\Org $executiveOrg Исполнительная власть
 * @property \app\models\Org $legislatureOrg Законодательная власть
 * @property \app\models\Structure $structure Структура
 * @property \app\models\GovermentForm $govermentForm Форма правления
 * @property \app\models\Region $capitalRegion Столичный регион
 * @property \app\models\Region[] $regions Список регионов
 * @property \app\models\Region[] $cities Список городов
 * @property \app\models\StateLicense[] $licenses Список экономических правил
 * @property \app\models\GovermentFieldValue[] $govermentFields Список пунктов конституции
 * @property \app\models\Party[] $parties Список партий
 * @property \app\models\User[] $users Список игроков
 */
class State extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'capital'], 'required'],
            [['legislature', 'executive', 'state_structure', 'goverment_form', 'group_id', 'allow_register_parties', 'population', 'sum_star', 'leader_can_drop_legislature', 'allow_register_holdings', 'register_parties_cost'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 10],
            [['capital', 'color'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'name'            => 'Name',
            'short_name'      => 'Short Name',
            'capital'         => 'Capital',
            'color'           => 'Color',
            'legislature'     => 'Legislature',
            'executive'       => 'Executive',
            'state_structure' => 'State Structure',
            'goverment_form'  => 'Goverment Form',
            'group_id'        => 'Group ID',
        ];
    }

    public function getLegislatureOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'legislature'));
    }

    public function getExecutiveOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'executive'));
    }

    public function getStructure()
    {
        return $this->hasOne('app\models\Structure', array('id' => 'state_structure'));
    }

    public function getGovermentForm()
    {
        return $this->hasOne('app\models\GovermentForm', array('id' => 'goverment_form'));
    }

    public function getCapitalRegion()
    {
        return $this->hasOne('app\models\Region', array('code' => 'capital'));
    }

    public function getRegions()
    {
        return $this->hasMany('app\models\Region', array('state_id' => 'id'))->orderBy('name');
    }

    public function getCities()
    {
        return $this->hasMany('app\models\Region', array('state_id' => 'id'))->orderBy('city');
    }

    public function getLicenses()
    {
        return $this->hasMany('app\models\StateLicense', array('state_id' => 'id'));
    }

    public function getGovermentFields()
    {
        return $this->hasMany('app\models\GovermentFieldValue', array('state_id' => 'id'));
    }

    public function getParties()
    {
        return $this->hasMany('app\models\Party', array('state_id' => 'id'));
    }

    public function getUsers()
    {
        return $this->hasMany('app\models\User', array('state_id' => 'id'));
    }

    /**
     * Подчищаем то что осталось после удаления государства
     */
    public function afterDelete()
    {
        if ($this->legislatureOrg)
            $this->legislatureOrg->delete();
        if ($this->executiveOrg)
            $this->executiveOrg->delete();

        foreach ($this->regions as $region) {
            $region->state_id = 0;
        }
        foreach ($this->govermentFields as $gf) {
            $gf->delete();
        }
        foreach ($this->parties as $party) {
            $party->delete();
        }
    }

    /**
     * Автосоздание всего, что нужно для создания государства
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $gftAr = GovermentFieldType::find()->all();
            foreach ($gftAr as $gft) {
                $gfv           = new GovermentFieldValue();
                $gfv->state_id = $this->id;
                $gfv->type_id  = $gft->id;
                $gfv->value    = $gft->default_value;
                $gfv->save();
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

}
