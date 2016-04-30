<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Utr,
    app\models\Tax,
    app\models\Org,
    app\models\User,
    app\models\Party,
    app\models\Region,
    app\models\Structure,
    app\models\GovermentForm,
    app\models\CoreCountry,
    app\models\CoreCountryState,
    app\models\licenses\LicenseRule,
    app\models\licenses\proto\LicenseProto,
    app\models\articles\Article,
    yii\db\Query;

/**
 * Государство. Таблица "states".
 *
 * @property integer $id
 * @property string $name Название
 * @property string $short_name Короткое название (2-3 буквы)
 * @property string $flag Ссылка на флаг
 * @property string $anthem Гимн на soundcloud
 * @property integer $capital ID региона-столицы
 * @property string $color Цвет страны на карте (с #)
 * @property integer $legislature ID организации законодательной власти
 * @property integer $executive ID организации исполнительной власти
 * @property integer $state_structure ID «структуры» государства
 * @property integer $goverment_form ID формы правления государства
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
 * @property double $balance
 * 
 * @property Org $executiveOrg Исполнительная власть
 * @property Org $legislatureOrg Законодательная власть
 * @property Structure $structure Структура
 * @property GovermentForm $govermentForm Форма правления
 * @property Region $capitalRegion Столичный регион
 * @property CoreCountry $core Государство-предок
 * @property Region[] $regions Список регионов
 * @property Region[] $cities Список городов
 * @property LicenseRule[] $licenses Список экономических правил
 * @property Article[] $articles Список пунктов конституции
 * @property Party[] $parties Список партий
 * @property User[] $users Список игроков
 * @property CoreCountryState[] $coreCountryStates Список привязок к корневым странам
 */
class State extends MyModel implements TaxPayer
{

    public function getUnnpType()
    {
        return Utr::TYPE_STATE;
    }
    
    public function isGoverment($stateId)
    {
        return $this->id === $stateId;
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::className(), array('unnp' => 'unnp'));
    }
    
    public function getUnnp() {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->utr = ($u) ? $u->id : 0;
            $this->save();
        } 
        return $this->utr;
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
            [['name', 'short_name', 'capital'], 'required'],
            [['capital', 'legislature', 'executive', 'state_structure', 'goverment_form', 'population', 'sum_star', 'allow_register_parties', 'leader_can_drop_legislature', 'allow_register_holdings', 'allow_register_holdings_noncitizens', 'register_holdings_mincap', 'register_holdings_noncitizens_mincap', 'register_holdings_maxcap', 'register_holdings_noncitizens_maxcap', 'register_parties_cost', 'core_id', 'mpfnig', 'mpfnih', 'utr'], 'integer'],
            [['register_holdings_cost', 'register_holdings_noncitizens_cost', 'balance'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 4],
            [['flag','anthem'], 'string', 'max' => 1000],
            [['color'], 'string', 'max' => 7]
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
            'anthem' => 'Anthem',
            'capital' => 'Capital',
            'color' => 'Color',
            'legislature' => 'Legislature',
            'executive' => 'Executive',
            'state_structure' => 'State Structure',
            'goverment_form' => 'Goverment Form',
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
        return $this->hasOne(Org::className(), array('id' => 'legislature'));
    }

    public function getExecutiveOrg()
    {
        return $this->hasOne(Org::className(), array('id' => 'executive'));
    }

    public function getStructure()
    {
        return $this->hasOne(Structure::className(), array('id' => 'state_structure'));
    }

    public function getGovermentForm()
    {
        return $this->hasOne(GovermentForm::className(), array('id' => 'goverment_form'));
    }

    public function getCore()
    {
        return $this->hasOne(CoreCountry::className(), array('id' => 'core_id'));
    }

    public function getCapitalRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'capital'));
    }

    public function getRegions()
    {
        return $this->hasMany(Region::className(), array('state_id' => 'id'))->orderBy('name');
    }

    public function getCities()
    {
        return $this->hasMany(Region::className(), array('state_id' => 'id'))->orderBy('city');
    }

    public function getLicenses()
    {
        return $this->hasMany(LicenseRule::className(), array('state_id' => 'id'));
    }

    public function getArticles()
    {
        return $this->hasMany(Article::className(), array('state_id' => 'id'))->orderBy('proto_id');
    }

    public function getParties()
    {
        return $this->hasMany(Party::className(), array('state_id' => 'id'));
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), array('state_id' => 'id'));
    }
    
    public function getTaxes()
    {
        return $this->hasMany(Tax::className(), array('state_id' => 'id'));
    }
    
    public function getCoreCountryStates()
    {
        return $this->hasMany(CoreCountryState::className(), ['state_id' => 'id']);
    }

    /**
     * 
     * @param LicenseProto $licenseType
     * @return LicenseRule
     */
    public function getLicenseRuleByPrototype($licenseType)
    {        
        return LicenseRule::findOrCreate(['state_id' => $this->id, 'proto_id' => $licenseType->id], true);
    }

    /**
     * 
     * @param CoreCountry $coreCountry
     * @return CoreCountryState
     */
    public function getCoreCountryState($coreCountry = null)
    {        
        if (is_null($coreCountry)) {
            $coreCountry = $this->core;
        }
        return CoreCountryState::findOrCreate(['state_id' => $this->id, 'core_id' => $coreCountry->id], true);
    }

    /**
     * Подчищаем то что осталось после удаления государства
     */
    public function afterDelete()
    {
        if ($this->legislatureOrg) {
            $this->legislatureOrg->delete();
        }

        if ($this->executiveOrg) {
            $this->executiveOrg->delete();
        }

        foreach ($this->regions as $region) {
            $region->state_id = 0;
            $region->save();
        }
        foreach ($this->articles as $gf) {
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
            
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function changeBalance($delta)
    {
        $this->balance += $delta;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getHtmlName($link = true)
    {
        if ($link) {
            return MyHtmlHelper::a(\yii\helpers\Html::img($this->flag,['style'=>'max-width:16px;max-height:12px']), "load_page('state-info',{'id':{$this->id}})").' '.MyHtmlHelper::a($this->name, "load_page('state-info',{'id':{$this->id}})");
        } else {
            return \yii\helpers\Html::img($this->flag,['style'=>'max-width:16px;max-height:12px']).' '.$this->name;
        }
    }

    public function getHtmlShortName()
    {
        return MyHtmlHelper::a(\yii\helpers\Html::img($this->flag,['style'=>'max-width:16px;max-height:12px']), "load_page('state-info',{'id':{$this->id}})").' '.MyHtmlHelper::a($this->short_name, "load_page('state-info',{'id':{$this->id}})");
    }

    public function getTaxStateId()
    {
        return 0;
    }

    public function isTaxedInState($stateId)
    {
        return false;
    }

    public function getUserControllerId()
    {
        return $this->executiveOrg->leader->user->id;
    }

    public function isUserController($userId)
    {
        return $this->executiveOrg->leader->user->id === $userId;        
    }
    
    public function calcPopulation()
    {
        $this->population = 0;
        foreach ($this->regions as $region) {
            $this->population += $region->population;
        }
    }
    
    public function updateCores()
    {
        $cores = [];
        foreach ($this->regions as $region) {
            foreach ($region->cores as $core) {
                if (isset($cores[$core->id])) {
                    $cores[$core->id]['count']++;
                } else {
                    $cores[$core->id] = [
                        'all' => intval($core->getRegions()->count()),
                        'count' => 1
                    ];
                }
            }
        }
        foreach ($cores as $coreId => $info) {
            $ar = ['percents' => $info['count']/$info['all']];
            CoreCountryState::findOrCreate([
                'state_id' => $this->id,
                'core_id' => $coreId
            ], true, $ar, $ar);
        }
    }
    
    public function calcSumStar()
    {
        $this->sum_star = intval((new Query())->from(User::tableName())->where([
            'state_id' => $this->id
        ])->sum('star'));
    }

}
