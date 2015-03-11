<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\Org;
use app\models\GovermentFieldValue;
use app\models\Notification;
use app\models\StateLicense;

/**
 * This is the model class for table "bill_types".
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
            'id' => 'ID',
            'name' => 'Name',
            'only_auto' => 'Only Auto',
            'only_dictator' => 'Only Dictator',
        ];
    }

    

    public function getFields()
    {
        return $this->hasMany('app\models\BillTypeField', array('bill_id' => 'id'));
    }
    
    public function accept($bill)
    {
        $data = json_decode($bill->data);
        switch ($this->id) {
            case 1: // Переименование государства
                $bill->state->name = $data->new_name;
                $bill->state->short_name = $data->new_short_name;
                $bill->state->save();
            break;
            case 2: // Перенос столицы государства
                $bill->state->capital = $data->new_capital;
                $bill->state->save();
            break;
            case 3: // Переименование региона
                $region = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->name = $data->new_name;
                    $region->save();
                }
            break;
            case 4: // Переименование города
                $region = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->city = $data->new_city_name;
                    $region->save();
                }
            break;
            case 5: // Дать региону независимость
                $region = Region::findByCode($data->region_code);
                if ($region && $region->state_id === $bill->state_id) {
                    $region->state_id = 0;
                    $region->save();
                }
            break;
            case 6: // Смена флага
                $bill->state->flag = $data->new_flag;
                $bill->state->save();
            break;
            case 7: // Поправка в конституцию
                $gfv = GovermentFieldValue::find()->where(['state_id'=>$bill->state_id,'type_id'=>$data->goverment_field_type])->one();
                if (is_null($gfv)) {
                    $gfv = new GovermentFieldValue();
                    $gfv->state_id = $bill->state_id;
                    $gfv->type_id = $data->goverment_field_type;
                }
                $gfv->value = $data->goverment_field_value;
                $gfv->save();
            break;
            case 8: // Смена цвета государства
                $bill->state->color = $data->new_color;
                $bill->state->save();
            break;
            case 9: // Сформировать законодательную власть
                
            break;
            case 10: // провести перевыборы
                $org_id = explode('_', $data->elected_variant)[0];
                $org = Org::findByPk($org_id);
                if ($org && $org->state_id === $bill->state_id) {
                    $org->next_elect = time()+48*60*60;
                    $org->save();
                }
            break;
            case 11: // Сменить порядок выдачи лицензий
                $sl = StateLicense::find()->where(['state_id'=>$bill->state_id,'license_id'=>$data->license_id])->one();
                if (is_null($sl)) {
                    $sl = new StateLicense();
                    $sl->state_id = $bill->state_id;
                    $sl->license_id = intval($data->license_id);
                }
                $sl->cost = floatval($data->cost);
                $sl->is_need_confirm = ($data->is_need_confirm ? 1 : 0);
                $sl->is_only_goverment = ($data->is_only_goverment ? 1 : 0);
                $sl->save();
            break;
            case 12: // Переименовать организацию
                $org = Org::findByPk($data->org_id);
                if ($org && $org->state_id === $bill->state_id) {
                    $org->name = $data->new_name;
                    $org->save();
                }
            break;
            
        }
        $bill->accepted = time();
        $bill->save();
        
        if ($bill->creatorUser) {
            Notification::send($bill->creatorUser->id, "Предложенный вами законопроект, предлагающий «".$this->name."» одобрен и вступил в силу");
        }
        
        return true;
    }
}
