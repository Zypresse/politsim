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
class GovermentFieldType extends MyModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'goverment_field_types';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
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
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'system_name' => 'System Name',
            'type' => 'Type',
            'hide' => 'Hide',
            'default_value' => 'Default',
        ];
    }

    public function setPublicAttributes() {
        return [
            'id',
            'name',
            'system_name',
            'type',
            'default_value'
        ];
    }

    /**
     * Разрешение регистрировать партии
     */
    const TYPE_ALLOW_REGISTER_PARTIES = 1;

    /**
     * Способ назначения членов исполнительной власти
     */
    const TYPE_EXECUTIVE_DEST = 2;

    /**
     * Способ назначения членов законодательной власти
     */
    const TYPE_LEGISLATURE_DEST = 3;

    /**
     * Способ назначения лидера исполнительной власти
     */
    const TYPE_EXECUTIVE_LEADER_DEST = 4;

    /**
     * Способ назначения лидера законодательной власти
     */
    const TYPE_LEGISLATURE_LEADER_DEST = 5;

    /**
     * Срок полномочий исполнительной власти в днях
     */
    const TYPE_EXECUTIVE_ELECT_PERIOD = 6;

    /**
     * Срок полномочий законодательной власти в днях
     */
    const TYPE_LEGISLATURE_ELECT_PERIOD = 7;

    /**
     * Разрешение лидеру страны единолично принимать законы
     */
    const TYPE_LEADER_CAN_MAKE_DICTATOR_BILLS = 8;

    /**
     * Разрешение лидеру страны предлагать законопроекты
     */
    const TYPE_LEADER_CAN_CREATE_BILLS = 9;

    /**
     *  Разрешение лидеру страны голосовать по законопроектам
     */
    const TYPE_LEADER_CAN_VOTE_FOR_BILLS = 10;

    /**
     * Разрешение лидеру страны иметь право вето по законопроектам
     */
    const TYPE_LEADER_CAN_VETO_BILLS = 11;

    /**
     * Разрешение членам законодательной власти предлагать законопроекты
     */
    const TYPE_LEGISLATURE_CAN_CREATE_BILLS = 12;

    /**
     * Разрешение членам законодательной власти голосовать по законопроектам
     */
    const TYPE_LEGISLATURE_CAN_VOTE_FOR_BILLS = 13;

    /**
     * Право лидера страны на роспуск парламента
     */
    const TYPE_LEADER_CAN_DROP_LEGISLATURE = 15;

    /**
     * Право создавать холдинги
     */
    const TYPE_ALLOW_REGISTER_HOLDINGS = 16;

    /**
     * Максимальный процент акций, который могут иметь иностранцы в гос. компаниях
     */
    const TYPE_MAX_PERCENT_FOR_NONCITIZENS_IN_GOS = 17;

    /**
     * Максимальный процент акций, который могут иметь иностранцы в частных компаниях
     */
    const TYPE_MAX_PERCENT_FOR_NONCITIZENS_IN_PRIVATE = 18;

    /**
     * Право парламента на отправку в отставку лидера государства
     */
    const TYPE_LEGISLATURE_CAN_DROP_STATELEADER = 19;
    
    /**
     * Право иностранцам регистрировать крупный бизнес
     */
    const TYPE_ALLOW_REGISTER_HOLDINGS_NONCITIZENS = 20;
    
    /**
     * Гос. пошлина за регистрацию компании
     */
    const TYPE_REGISTER_HOLDINGS_COST = 21;
    
    /**
     * Гос. пошлина за регистрацию компании для иностранцев
     */
    const TYPE_REGISTER_HOLDINGS_NONCITIZENS_COST = 22;
    
    /**
     * Минимальная стартовая капитализация компании
     */
    const TYPE_REGISTER_HOLDINGS_MINCAP = 23;
    
    /**
     * Минимальная стартовая капитализация компании для иностранцев
     */
    const TYPE_REGISTER_HOLDINGS_NONCITIZENS_MINCAP = 24;
    
    /**
     * Максимальная стартовая капитализация компании
     */
    const TYPE_REGISTER_HOLDINGS_MAXCAP = 25;

    /**
     * Максимальная стартовая капитализация компании для иностранцев
     */
    const TYPE_REGISTER_HOLDINGS_NONCITIZENS_MAXCAP = 26;

    /**
     * Установление реальных настроек по этому пункту конституции
     * @param GovermentFieldValue $value
     */
    public function syncronize($value, $state = null) {
        if (is_null($state)) {
            $state = $value->state;
        }
        switch ($this->id) {
            case static::TYPE_ALLOW_REGISTER_PARTIES:
                $value->value = ($value->value) ? 1 : 0;
                $state->allow_register_parties = $value->value;
                $state->save();
                break;
            case static::TYPE_EXECUTIVE_DEST:
                if ($state->executiveOrg) {
                    $state->executiveOrg->dest = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEGISLATURE_DEST:
                if ($state->legislatureOrg) {
                    $state->legislatureOrg->dest = $value->value;
                    $state->legislatureOrg->save();
                }
                break;
            case static::TYPE_EXECUTIVE_LEADER_DEST: // 
                if ($state->executiveOrg) {
                    $state->executiveOrg->leader_dest = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEGISLATURE_LEADER_DEST: // 
                if ($state->legislatureOrg) {
                    $state->legislatureOrg->leader_dest = $value->value;
                    $state->legislatureOrg->save();
                }
                break;
            case static::TYPE_EXECUTIVE_ELECT_PERIOD: // 
                if ($state->executiveOrg) {
                    if ($value->value > 1) {
                        $state->executiveOrg->elect_period = intval($value->value);
                    } else {
                        $value->value = '-1';
                        $state->executiveOrg->elect_period = -1;
                    }
                    $state->executiveOrg->save();  
                }                  
                break;
            case static::TYPE_LEGISLATURE_ELECT_PERIOD: // 
                if ($state->legislatureOrg) {
                    if ($value->value > 1) {
                        $state->legislatureOrg->elect_period = intval($value->value);
                    } else {
                        $value->value = '-1';
                        $state->legislatureOrg->elect_period = -1;
                    }
                    $state->legislatureOrg->save();
                }
                break;
            case static::TYPE_LEADER_CAN_MAKE_DICTATOR_BILLS: // 
                if ($state->executiveOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->executiveOrg->leader_can_make_dicktator_bills = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEADER_CAN_CREATE_BILLS: // Разрешение лидеру страны предлагать законопроекты
                if ($state->executiveOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->executiveOrg->leader_can_create_bills = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEADER_CAN_VOTE_FOR_BILLS: // Разрешение лидеру страны голосовать по законопроектам
                if ($state->executiveOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->executiveOrg->leader_can_vote_for_bills = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEADER_CAN_VETO_BILLS: // Разрешение лидеру страны иметь право вето по законопроектам
                if ($state->executiveOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->executiveOrg->leader_can_veto_bills = $value->value;
                    $state->executiveOrg->save();
                }
                break;
            case static::TYPE_LEGISLATURE_CAN_CREATE_BILLS: // Разрешение членам законодательной власти предлагать законопроекты
                if ($state->legislatureOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->legislatureOrg->can_create_bills = $value->value;
                    $state->legislatureOrg->save();
                }
                break;
            case static::TYPE_LEGISLATURE_CAN_VOTE_FOR_BILLS: // Разрешение членам законодательной власти голосовать по законопроектам
                if ($state->legislatureOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->legislatureOrg->can_vote_for_bills = $value->value;
                    $state->legislatureOrg->save();
                }
                break;
            case static::TYPE_LEADER_CAN_DROP_LEGISLATURE: // Право лидера страны на роспуск парламента
                $value->value = ($value->value) ? 1 : 0;
                $state->leader_can_drop_legislature = $value->value;
                $state->save();
                break;
            case static::TYPE_ALLOW_REGISTER_HOLDINGS: // Право создавать холдинги
                $value->value = ($value->value) ? 1 : 0;
                $state->allow_register_holdings = $value->value;
                $state->save();
                break;
            case static::TYPE_MAX_PERCENT_FOR_NONCITIZENS_IN_GOS: // Максимальный процент акций, который могут иметь иностранцы в гос. компаниях
                $value->value = intval($value->value);
                if ($value->value < 0) {
                    $value->value = 0;
                }
                if ($value->value > 100) {
                    $value->value = 100;
                }

                $state->mpfnig = $value->value;
                $state->save();
                break;
            case static::TYPE_MAX_PERCENT_FOR_NONCITIZENS_IN_PRIVATE: // Максимальный процент акций, который могут иметь иностранцы в частных компаниях
                $value->value = intval($value->value);
                if ($value->value < 0) {
                    $value->value = 0;
                }
                if ($value->value > 100) {
                    $value->value = 100;
                }

                $state->mpfnih = $value->value;
                $state->save();
                break;

            case static::TYPE_LEGISLATURE_CAN_DROP_STATELEADER: // Право парламента на отправку в отставку лидера государства
                if ($state->legislatureOrg) {
                    $value->value = ($value->value) ? 1 : 0;
                    $state->legislatureOrg->can_drop_stateleader = $value->value;
                    $state->save();
                }
                break;

            case static::TYPE_ALLOW_REGISTER_HOLDINGS_NONCITIZENS:
                $value->value = ($value->value) ? 1 : 0;
                $state->allow_register_holdings_noncitizens = $value->value;
                $state->save();
                break;

            case static::TYPE_REGISTER_HOLDINGS_COST:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : 0;
                $state->register_holdings_cost = $value->value;
                $state->save();
                break;

            case static::TYPE_REGISTER_HOLDINGS_NONCITIZENS_COST:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : 0;
                $state->register_holdings_noncitizens_cost = $value->value;
                $state->save();
                break;
            
            case static::TYPE_REGISTER_HOLDINGS_MINCAP:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : 0;
                $state->register_holdings_mincap = $value->value;
                $state->save();
                break;
            
            case static::TYPE_REGISTER_HOLDINGS_NONCITIZENS_MINCAP:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : 0;
                $state->register_holdings_noncitizens_mincap = $value->value;
                $state->save();
                break;
            
            case static::TYPE_REGISTER_HOLDINGS_MAXCAP:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : -1;
                $state->register_holdings_maxcap = $value->value;
                $state->save();
                break;
            
            case static::TYPE_REGISTER_HOLDINGS_NONCITIZENS_MAXCAP:
                $value->value = floatval($value->value) > 0 ? floatval($value->value) : -1;
                $state->register_holdings_noncitizens_maxcap = $value->value;
                $state->save();
                break;
        }
        
        $value->noSync = true;
        $value->value = (string)$value->value;
        $value->save();
    }

}
