<?php

namespace app\models;

use app\components\NalogPayer;
use app\models\GovermentFieldType;
use app\models\GovermentFieldValue;

/**
 * Государство. Таблица "states".
 *
 * @property integer $id
 * @property string $name Название
 * @property string $short_name Короткое название (2-3 буквы)
 * @property string $flag Ссылка на флаг
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
 * @property integer $allow_register_holdings_noncitizens Разрешено ли регистировать АО нерезидентам
 * @property double $register_holdings_cost Пошлина за регистрацию АО
 * @property double $register_holdings_noncitizens_cost Пошлина за регистрацию АО для нерезидентов
 * @property integer $register_holdings_mincap Минимальная стартовая капитализация АО
 * @property integer $register_holdings_noncitizens_mincap Минимальная стартовая капитализация АО для нерезидентов
 * @property integer $register_holdings_maxcap Максимальная стартовая капитализация АО
 * @property integer $register_holdings_noncitizens_maxcap Максимальная стартовая капитализация АО для нерезидентов
 * @property integer $register_parties_cost Стоимость регистрации партии
 * @property integer $core_id ID коренного государства наследником которого является
 * @property integer $mpfnig Максимальный процент акций, который могут иметь иностранцы в гос. компаниях
 * @property integer $mpfnih Максимальный процент акций, который могут иметь иностранцы в частных компаниях
 * 
 * @property \app\models\Org $executiveOrg Исполнительная власть
 * @property \app\models\Org $legislatureOrg Законодательная власть
 * @property \app\models\Structure $structure Структура
 * @property \app\models\GovermentForm $govermentForm Форма правления
 * @property \app\models\Region $capitalRegion Столичный регион
 * @property \app\models\CoreCountry $core Государство-предок
 * @property \app\models\Region[] $regions Список регионов
 * @property \app\models\Region[] $cities Список городов
 * @property \app\models\StateLicense[] $licenses Список экономических правил
 * @property \app\models\GovermentFieldValue[] $govermentFields Список пунктов конституции
 * @property \app\models\Party[] $parties Список партий
 * @property \app\models\User[] $users Список игроков
 */
class State extends NalogPayer
{

    protected function getField()
    {
        return 'state';
    }
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
            [['name', 'short_name', 'flag', 'capital'], 'required'],
            [['legislature', 'executive', 'state_structure', 'goverment_form', 'group_id', 'population', 'sum_star', 'allow_register_parties', 'leader_can_drop_legislature', 'allow_register_holdings', 'allow_register_holdings_noncitizens', 'register_holdings_mincap', 'register_holdings_noncitizens_mincap', 'register_holdings_maxcap', 'register_holdings_noncitizens_maxcap', 'register_parties_cost', 'core_id', 'mpfnig', 'mpfnih'], 'integer'],
            [['register_holdings_cost', 'register_holdings_noncitizens_cost'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 4],
            [['flag'], 'string', 'max' => 1000],
            [['capital', 'color'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
            'flag' => 'Flag',
            'capital' => 'Capital',
            'color' => 'Color',
            'legislature' => 'Legislature',
            'executive' => 'Executive',
            'state_structure' => 'State Structure',
            'goverment_form' => 'Goverment Form',
            'group_id' => 'Group ID',
            'population' => 'Population',
            'sum_star' => 'Sum Star',
            'allow_register_parties' => 'Allow Register Parties',
            'leader_can_drop_legislature' => 'Leader Can Drop Legislature',
            'allow_register_holdings' => 'Allow Register Holdings',
            'allow_register_holdings_noncitizens' => 'Allow Register Holdings Noncitizens',
            'register_holdings_cost' => 'Register Holdings Cost',
            'register_holdings_noncitizens_cost' => 'Register Holdings Noncitizens Cost',
            'register_holdings_mincap' => 'Register Holdings Mincap',
            'register_holdings_noncitizens_mincap' => 'Register Holdings Noncitizens Mincap',
            'register_holdings_maxcap' => 'Register Holdings Maxcap',
            'register_holdings_noncitizens_maxcap' => 'Register Holdings Noncitizens Maxcap',
            'register_parties_cost' => 'Register Parties Cost',
            'core_id' => 'Core ID',
            'mpfnig' => 'Mpfnig',
            'mpfnih' => 'Mpfnih',
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

    public function getCore()
    {
        return $this->hasOne('app\models\CoreCountry', array('id' => 'core_id'));
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
            $region->save();
        }
        foreach ($this->govermentFields as $gf) {
            $gf->delete();
        }
        foreach ($this->licenses as $l) {
            $l->delete();
        }
        foreach ($this->parties as $party) {
            $party->delete();
        }
        
        foreach ($this->users as $user) {
            $user->state_id = 0;
            $user->party_id = 0;
            $user->post_id = 0;
            $user->save();
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
