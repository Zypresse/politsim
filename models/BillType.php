<?php

namespace app\models;

use app\components\MyModel;
use app\models\Org;
use app\models\State;
use app\models\GovermentFieldValue;
use app\models\Notification;
use app\models\StateLicense;
use app\models\CoreCountry;
use app\components\MyHtmlHelper;

/**
 * Тип законопроекта. Таблица "bill_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $only_auto
 * @property integer $only_dictator
 */
class BillType extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'only_auto', 'only_dictator'], 'required'],
            [['only_auto', 'only_dictator'], 'integer'],
            [['name'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'name'          => 'Name',
            'only_auto'     => 'Only Auto',
            'only_dictator' => 'Only Dictator',
        ];
    }

    public function getFields()
    {
        return $this->hasMany('app\models\BillTypeField', array('bill_id' => 'id'));
    }

    /**
     * Переименование государства
     */
    const TYPE_RENAME_STATE = 1;

    /**
     * Перенос столицы государства
     */
    const TYPE_CHANGE_CAPITAL = 2;

    /**
     * Переименование региона
     */
    const TYPE_RENAME_REGION = 3;

    /**
     * Переименование города
     */
    const TYPE_RENAME_CITY = 4;

    /**
     * Дать региону независимость
     */
    const TYPE_INDEPENDENCE_REGION = 5;

    /**
     * Смена флага
     */
    const TYPE_CHANGE_FLAG = 6;

    /**
     * Поправка в конституцию
     */
    const TYPE_CONSTITUTION_UPDATE = 7;

    /**
     * Смена цвета государства
     */
    const TYPE_CHANGE_COLOR = 8;

    /**
     * Сформировать законодательную власть
     */
    const TYPE_FORM_LEGISLATURE = 9;

    /**
     * провести перевыборы
     */
    const TYPE_MAKE_REELECTS = 10;

    /**
     * Сменить порядок выдачи лицензий
     */
    const TYPE_CHANGE_STATELICENSE = 11;

    /**
     * Переименовать организацию
     */
    const TYPE_RENAME_ORG = 12;

    /**
     * Создание сателлита
     */
    const TYPE_CREATE_SATELLITE = 13;
    
    /**
     * Отправить главу страны в отставку
     */
    const TYPE_DROP_STATELEADER = 14;

    /**
     * Принятие законопроекта
     * @param Bill $bill
     * @return boolean
     */
    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        $data = json_decode($bill->data);
        switch ($this->id) {
            case static::TYPE_RENAME_STATE: // Переименование государства
                $bill->state->name       = $data->new_name;
                $bill->state->short_name = $data->new_short_name;
                $bill->state->save();
                break;
            case static::TYPE_CHANGE_CAPITAL: // Перенос столицы государства
                $bill->state->capital    = $data->new_capital;
                $bill->state->save();
                break;
            case static::TYPE_RENAME_REGION: // Переименование региона
                $region                  = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->name = $data->new_name;
                    $region->save();
                }
                break;
            case static::TYPE_RENAME_CITY: // Переименование города
                $region = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->city = $data->new_city_name;
                    $region->save();
                }
                break;
            case static::TYPE_INDEPENDENCE_REGION: // Дать региону независимость
                $region = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->state_id = 0;
                    $region->save();
                }
                break;
            case static::TYPE_CHANGE_FLAG: // Смена флага
                $bill->state->flag = $data->new_flag;
                $bill->state->save();
                break;
            case static::TYPE_CONSTITUTION_UPDATE: // Поправка в конституцию
                $gfv               = GovermentFieldValue::find()->where(['state_id' => $bill->state_id, 'type_id' => $data->goverment_field_type])->one();
                if (is_null($gfv)) {
                    $gfv           = new GovermentFieldValue();
                    $gfv->state_id = $bill->state_id;
                    $gfv->type_id  = $data->goverment_field_type;
                }
                $gfv->value         = $data->goverment_field_value;
                $gfv->save();
                break;
            case static::TYPE_CHANGE_COLOR: // Смена цвета государства
                $bill->state->color = $data->new_color;
                $bill->state->save();
                break;
            case static::TYPE_FORM_LEGISLATURE: // Сформировать законодательную власть
                if (is_null($bill->state->legislatureOrg)) {
                    $org                      = Org::generate($bill->state, Org::LEGISLATURE_PARLIAMENT10);
                    $bill->state->legislature = $org->id;
                    $bill->state->save();
                }
                break;
            case static::TYPE_MAKE_REELECTS: // провести перевыборы
                $org_id = explode('_', $data->elected_variant)[0];
                $org    = Org::findByPk($org_id);
                if ($org && $org->state_id === $bill->state_id) {
                    $org->next_elect = time() + 48 * 60 * 60;
                    $org->save();
                }
                break;
            case static::TYPE_CHANGE_STATELICENSE: // Сменить порядок выдачи лицензий
                $sl = StateLicense::find()->where(['state_id' => $bill->state_id, 'license_id' => $data->license_id])->one();
                if (is_null($sl)) {
                    $sl             = new StateLicense();
                    $sl->state_id   = $bill->state_id;
                    $sl->license_id = intval($data->license_id);
                }
                $sl->cost              = floatval($data->cost);
                $sl->is_need_confirm   = ($data->is_need_confirm ? 1 : 0);
                $sl->is_only_goverment = ($data->is_only_goverment ? 1 : 0);
                $sl->save();
                break;
            case static::TYPE_RENAME_ORG: // Переименовать организацию
                $org                   = Org::findByPk($data->org_id);
                if ($org && $org->state_id === $bill->state_id) {
                    $org->name = $data->new_name;
                    $org->save();
                }
                break;

            case static::TYPE_CREATE_SATELLITE: // Создание сателлита
                if ($data->core_id) {
                    $core = CoreCountry::findByPk($data->core_id);
                }
                $region = Region::findByCode($data->new_capital);
                if ($region && $region->state_id === $bill->state_id) {
                    $state                              = new State();
                    $state->name                        = $data->new_name;
                    $state->short_name                  = $data->new_short_name;
                    $state->flag                        = "http://placehold.it/300x200/eeeeee/000000&text=" . urlencode(MyHtmlHelper::transliterate($data->new_short_name));
                    $state->capital                     = $data->new_capital;
                    $state->color                       = MyHtmlHelper::getSomeColor(mt_rand(0, 100), true);
                    $state->core_id                     = $data->core_id;
                    $state->allow_register_parties      = 1;
                    $state->leader_can_drop_legislature = 1;
                    $state->allow_register_holdings     = 1;
                    $state->register_parties_cost       = 0;
                    $state->save();

                    $executive        = Org::generate($state, Org::EXECUTIVE_PRESIDENT);
                    $state->executive = $executive->id;

                    $legislature        = Org::generate($state, Org::LEGISLATURE_PARLIAMENT10);
                    $state->legislature = $legislature->id;

                    $state->save();

                    if ($data->core_id && $core) {
                        foreach ($core->regions as $region) {
                            if ($region->state_id === $bill->state_id && !($region->isCapital())) {
                                $region->state_id = $state->id;
                                $region->save();
                            }
                        }
                    }
                }
                break;
            case static::TYPE_DROP_STATELEADER: // Отставка лидера государства
                if ($bill->state->executiveOrg->leader->user) {
                    $bill->state->executiveOrg->leader->unlink('user', $bill->state->executiveOrg->leader->user);
                }
                break;
        }
        $bill->accepted = time();
        $bill->save();

        if ($bill->creatorUser) {
            Notification::send($bill->creatorUser->id, "Предложенный вами законопроект, предлагающий «" . $this->name . "» одобрен и вступил в силу");
        }
        
        foreach ($bill->votes as $vote) {
            $vote->delete();
        }

        return true;
    }

}
