<?php

namespace app\models;

use app\components\MyModel;

/**
 * Тип пункта конституции. Таблица "goverment_field_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $system_name
 * @property string $type
 * @property integer $hide
 * @property string $default_value
 */
class GovermentFieldType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goverment_field_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'system_name', 'type', 'default_value'], 'required'],
            [['hide'], 'integer'],
            [['name', 'default_value'], 'string', 'max' => 1000],
            [['system_name', 'type'], 'string', 'max' => 255]
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
            'system_name' => 'System Name',
            'type' => 'Type',
            'hide' => 'Hide',
            'default_value' => 'Default',
        ];
    }

    public function setPublicAttributes()
    { 
        return [
            'id',
            'name',
            'system_name',
            'type',
            'default_value'
        ];
    }
    
    /**
     * Установление реальных настроек по этому пункту конституции
     * @param \StdClass $value
     */
    public function syncronize($value)
    {
        switch ($this->id) {
            case 1: // Разрешение регистрировать партии
                $value->state->allow_register_parties = ($value->value) ? 1 : 0;
                $value->state->save();
            break;
            case 2: // Способ назначения членов исполнительной власти
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->dest = $value->value;
                    $value->state->executiveOrg->save();
                }
            break;
            case 3: // Способ назначения членов законодательной власти
                if ($value->state->legislatureOrg) {
                    $value->state->legislatureOrg->dest = $value->value;
                    $value->state->legislatureOrg->save();
                }
            break;
            case 4: // Способ назначения лидера исполнительной власти
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->leader_dest = $value->value;
                    $value->state->executiveOrg->save();
                }
            break;
            case 5: // Способ назначения лидера законодательной власти
                if ($value->state->legislatureOrg) {
                    $value->state->legislatureOrg->leader_dest = $value->value;
                    $value->state->legislatureOrg->save();
                }
            break;
            case 6: // Срок полномочий исполнительной власти в днях
                if ($value->state->executiveOrg && $value->value > 1) {
                    $value->state->executiveOrg->elect_period = intval($value->value);
                    $value->state->executiveOrg->save();
                }
            break;
            case 7: // Срок полномочий законодательной власти в днях
                if ($value->state->legislatureOrg && $value->value > 1) {
                    $value->state->legislatureOrg->elect_period = intval($value->value);
                    $value->state->legislatureOrg->save();
                }
            break;
            case 8: // Разрешение лидеру страны единолично принимать законы
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->leader_can_make_dicktator_bills = ($value->value) ? 1 : 0;
                    $value->state->executiveOrg->save();
                }
            break;
            case 9: // Разрешение лидеру страны предлагать законопроекты
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->leader_can_create_bills = ($value->value) ? 1 : 0;
                    $value->state->executiveOrg->save();
                }
            break;
            case 10: // Разрешение лидеру страны голосовать по законопроектам
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->leader_can_vote_for_bills = ($value->value) ? 1 : 0;
                    $value->state->executiveOrg->save();
                }
            break;
            case 11: // Разрешение лидеру страны иметь право вето по законопроектам
                if ($value->state->executiveOrg) {
                    $value->state->executiveOrg->leader_can_veto_bills = ($value->value) ? 1 : 0;
                    $value->state->executiveOrg->save();
                }
            break;
            case 12: // Разрешение членам законодательной власти предлагать законопроекты
                if ($value->state->legislatureOrg) {
                    $value->state->legislatureOrg->can_create_bills = ($value->value) ? 1 : 0;
                    $value->state->legislatureOrg->save();
                }
            break;
            case 13: // Разрешение членам законодательной власти голосовать по законопроектам
                if ($value->state->legislatureOrg) {
                    $value->state->legislatureOrg->can_vote_for_bills = ($value->value) ? 1 : 0;
                    $value->state->legislatureOrg->save();
                }
            break;
            case 15: // Право лидера страны на роспуск парламента
                $value->state->leader_can_drop_legislature = ($value->value) ? 1 : 0;
                $value->state->save();
            break;
            case 16: // Право создавать холдинги
                $value->state->allow_register_holdings = ($value->value) ? 1 : 0;
                $value->state->save();
            break;
            
        }
    }
}
