<?php

namespace app\models\politics\bills;

use Yii,
    app\models\politics\bills\Bill,
    app\models\politics\State;

/**
 * Прототип законопроекта
 */
abstract class BillProto implements BillProtoInterface
{
    
    /**
     * Переименовать государство
     */
    const RENAME_STATE = 1;
    
    /**
     * Переименовать регион
     */
    const RENAME_REGION = 2;
    
    /**
     * Переименовать город
     */
    const RENAME_CITY = 3;
    
    /**
     * Создать агенство по шаблону
     */
    const CREATE_AGENCY = 4;
    
    /**
     * Переименовать агенство
     */
    const RENAME_AGENCY = 5;
    
    /**
     * Сменить флаг государства
     */
    const CHANGE_FLAG_STATE = 6;
    
    /**
     * Сменить флаг региона
     */
    const CHANGE_FLAG_REGION = 7;
    
    /**
     * Сменить флаг города
     */
    const CHANGE_FLAG_CITY = 8;
    
    /**
     * Сменить гимн государства
     */
    const CHANGE_ANTHEM_STATE = 9;
    
    /**
     * Сменить гимн региона
     */
    const CHANGE_ANTHEM_REGION = 10;
    
    /**
     * Сменить гимн города
     */
    const CHANGE_ANTHEM_CITY = 11;
    
    /**
     * Сменить партийную политику
     */
    const PARTIES_POLITIC = 12;
    
    /**
     * Создать новый регион
     */
    const CREATE_REGION = 13;
    
    /**
     * Включить один регион в состав другого
     */
    const IMPLODE_REGIONS = 14;
    
    /**
     * Изменить границу регионов
     */
    const CHANGE_REGIONS_BORDER = 15;
    
    /**
     * Изменить столицу государства
     */
    const CHANGE_CAPITAL_STATE = 16;
    
    /**
     * Изменить столицу региона
     */
    const CHANGE_CAPITAL_REGION = 17;
    
    /**
     * Изменить разрешение юзерам занимать несколько должностей
     */
    const MULTIPOST_POLITIC = 18;
    
    /**
     * Выделить новый избирательный округ
     */
    const CREATE_DISTRICT = 19;
    
    /**
     * Объединить округа
     */
    const IMPLODE_DISTRICTS = 20;
    
    /**
     * Изменить границы округов
     */
    const CHANGE_DISTRICTS_BORDER = 21;
    
    /**
     * Изменить способ назначения поста
     */
    const POST_DESTIGNATION = 22;
    
    /**
     * Создать новый пост
     */
    const CREATE_POST = 23;
    
    /**
     * Изменить права поста
     */
    const POST_POWERS = 24;
    
    /**
     * Переименовать пост
     */
    const RENAME_POST = 25;
    
    /**
     * Разрешить/запретить частный бизнес
     */
    const BUSINESS = 26;
    
    /**
     * Удалить пост
     */
    const DELETE_POST = 27;
    
    /**
     * Уволить с поста
     */
    const FIRE_FROM_POST = 28;
    
    /**
     * Создать компанию
     */
    const COMPANY_CREATE = 29;
    
    /**
     * Управление правилами выдачи лицензий
     */
    const LICENSE_RULE = 30;
    
    /**
     * Установить пост, руководящий регионом
     */
    const SET_REGION_LEADER = 31;
    
    /**
     * Установить пост, руководящий городом
     */
    const SET_CITY_LEADER = 32;
    
    /**
     * Установить пост, руководящий страной
     */
    const SET_STATE_LEADER = 33;
    
    /**
     * Установить пост, руководязий агенством
     */
    const SET_AGENCY_LEADER = 34;
    
    /**
     * Установить пост/агенство/ещё что-то государственное, как владельца гос. компании
     */
    const SET_SHAREHOLDER = 35;
    
    /**
     * Изменить настройки создания зп
     */
    const BILLS = 36;
    
    /**
     * Изменить гос. валюту
     */
    const CURRENCY = 37;

    public static function findAll()
    {
        return [
            static::RENAME_STATE => Yii::t('app/bills', 'Rename state'),
            static::CHANGE_FLAG_STATE => Yii::t('app/bills', 'Change state flag'),
            static::CHANGE_ANTHEM_STATE => Yii::t('app/bills', 'Change state anthem'),
            static::CHANGE_CAPITAL_STATE => Yii::t('app/bills', 'Change state capital'),
            static::SET_STATE_LEADER => Yii::t('app/bills', 'Set state leader'),
            
            static::CREATE_AGENCY => Yii::t('app/bills', 'Create new agency'),
            static::RENAME_AGENCY => Yii::t('app/bills', 'Rename agency'),
            static::SET_AGENCY_LEADER => Yii::t('app/bills', 'Set agency leader'),
            
            static::POST_DESTIGNATION => Yii::t('app/bills', 'Change agency post destignation type'),
            static::CREATE_POST => Yii::t('app/bills', 'Create new agency post'),
            static::POST_POWERS => Yii::t('app/bills', 'Create agency post powers'),
            static::RENAME_POST => Yii::t('app/bills', 'Rename agency post'),
            static::DELETE_POST => Yii::t('app/bills', 'Delete agency post'),
            static::FIRE_FROM_POST => Yii::t('app/bills', 'Fire from agency post'),
            
            static::RENAME_REGION => Yii::t('app/bills', 'Rename region'),
            static::CHANGE_FLAG_REGION => Yii::t('app/bills', 'Change region flag'),
            static::CHANGE_ANTHEM_REGION => Yii::t('app/bills', 'Change region anthem'),
            static::CHANGE_CAPITAL_REGION => Yii::t('app/bills', 'Change region capital'),
            static::CREATE_REGION => Yii::t('app/bills', 'Seduce new region'),
            static::IMPLODE_REGIONS => Yii::t('app/bills', 'Implode regions'),
            static::CHANGE_REGIONS_BORDER => Yii::t('app/bills', 'Change regions border'),
            static::SET_REGION_LEADER => Yii::t('app/bills', 'Set region leader'),
            
            static::RENAME_CITY => Yii::t('app/bills', 'Rename city'),
            static::CHANGE_FLAG_CITY => Yii::t('app/bills', 'Change city flag'),
            static::CHANGE_ANTHEM_CITY => Yii::t('app/bills', 'Change city anthem'),
            static::SET_CITY_LEADER => Yii::t('app/bills', 'Set city leader'),
            
            static::CREATE_DISTRICT => Yii::t('app/bills', 'Seduce new electoral district'),
            static::IMPLODE_DISTRICTS => Yii::t('app/bills', 'Implode electoral districts'),
            static::CHANGE_DISTRICTS_BORDER => Yii::t('app/bills', 'Change electoral districts border'),
            
            static::PARTIES_POLITIC => Yii::t('app/bills', 'Change parties politic'),
            static::MULTIPOST_POLITIC => Yii::t('app/bills', 'Allow/disallow more than one agency post to user'),
            static::BUSINESS => Yii::t('app/bills', 'Allow/disallow buisness'),
            static::BILLS => Yii::t('app/bills', 'Change bills creating rules'),
            static::CURRENCY => Yii::t('app/bills', 'Change state currency'),
            
            static::COMPANY_CREATE => Yii::t('app/bills', 'Create goverment company'),            
            static::LICENSE_RULE => Yii::t('app/bills', 'Licenses rules management'),
            static::SET_SHAREHOLDER => Yii::t('app/bills', 'Set goverment company shareholder'),
        ];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassNameByType(int $type) : string
    {
        $classes = [
            static::RENAME_STATE => 'state\\Rename',
            static::RENAME_REGION => 'region\\Rename',
            static::RENAME_CITY => 'city\\Rename',
            static::CREATE_AGENCY => 'agency\\Create',
            static::RENAME_AGENCY => 'agency\\Rename',
            static::CHANGE_FLAG_STATE => 'state\\ChangeFlag',
            static::CHANGE_FLAG_REGION => 'region\\ChangeFlag',
            static::CHANGE_FLAG_CITY => 'city\\ChangeFlag',
            static::CHANGE_ANTHEM_STATE => 'state\\ChangeAnthem',
            static::CHANGE_ANTHEM_REGION => 'region\\ChangeAnthem',
            static::CHANGE_ANTHEM_CITY => 'city\\ChangeAnthem',
            static::PARTIES_POLITIC => 'constitution\\Parties',
            static::CREATE_REGION => 'region\\Create',
            static::IMPLODE_REGIONS => 'region\\Implode',
            static::CHANGE_REGIONS_BORDER => 'region\\ChangeBorders',
            static::CHANGE_CAPITAL_STATE => 'state\\ChangeCapital',
            static::CHANGE_CAPITAL_REGION => 'region\\ChangeCapital',
            static::MULTIPOST_POLITIC => 'constitution\\Multipost',
            static::CREATE_DISTRICT => 'district\\Create',
            static::IMPLODE_DISTRICTS => 'district\\Implode',
            static::CHANGE_DISTRICTS_BORDER => 'district\\ChangeBorders',
            static::POST_DESTIGNATION => 'post\\Destignation',
            static::CREATE_POST => 'post\\Create',
            static::POST_POWERS => 'post\\Powers',
            static::RENAME_POST => 'post\\Rename',
            static::BUSINESS => 'constitution\\Business',
            static::DELETE_POST => 'post\\Delete',
            static::FIRE_FROM_POST => 'post\\Fire',
            static::COMPANY_CREATE => 'company\\Create',
            static::LICENSE_RULE => 'company\\LicenseRule',
            static::SET_REGION_LEADER => 'region\\SetLeader',
            static::SET_CITY_LEADER => 'city\\SetLeader',
            static::SET_STATE_LEADER => 'state\\SetLeader',
            static::SET_AGENCY_LEADER => 'agency\\SetLeader',
            static::SET_SHAREHOLDER => 'company\\SetShareholder',
            static::BILLS => 'constitution\\Bills',
            static::CURRENCY => 'constitution\\Currency',
        ];
        
        return '\\app\\models\\politics\\bills\\prototypes\\'.$classes[$type];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getViewByType(int $type) : string
    {
        $views = [
            static::RENAME_STATE => 'state/rename',
            static::RENAME_REGION => 'region/rename',
            static::RENAME_CITY => 'city/rename',
            static::CREATE_AGENCY => 'agency/create',
            static::RENAME_AGENCY => 'agency/rename',
            static::CHANGE_FLAG_STATE => 'state/change-flag',
            static::CHANGE_FLAG_REGION => 'region/change-flag',
            static::CHANGE_FLAG_CITY => 'city/change-flag',
            static::CHANGE_ANTHEM_STATE => 'state/change-anthem',
            static::CHANGE_ANTHEM_REGION => 'region/change-anthem',
            static::CHANGE_ANTHEM_CITY => 'city/change-anthem',
            static::PARTIES_POLITIC => 'constitution/parties',
            static::CREATE_REGION => 'region/create',
            static::IMPLODE_REGIONS => 'region/implode',
            static::CHANGE_REGIONS_BORDER => 'region/change-borders',
            static::CHANGE_CAPITAL_STATE => 'state/change-capital',
            static::CHANGE_CAPITAL_REGION => 'region/change-capital',
            static::MULTIPOST_POLITIC => 'constitution/multipost',
            static::CREATE_DISTRICT => 'district/create',
            static::IMPLODE_DISTRICTS => 'district/implode',
            static::CHANGE_DISTRICTS_BORDER => 'district/change-borders',
            static::POST_DESTIGNATION => 'post/destignation',
            static::CREATE_POST => 'post/create',
            static::POST_POWERS => 'post/powers',
            static::RENAME_POST => 'post/rename',
            static::BUSINESS => 'constitution/business',
            static::DELETE_POST => 'post/delete',
            static::FIRE_FROM_POST => 'post/fire',
            static::COMPANY_CREATE => 'company/create',
            static::LICENSE_RULE => 'company/license-rule',
            static::SET_REGION_LEADER => 'region/set-leader',
            static::SET_CITY_LEADER => 'city/set-leader',
            static::SET_STATE_LEADER => 'state/set-leader',
            static::SET_AGENCY_LEADER => 'agency/set-leader',
            static::SET_SHAREHOLDER => 'company/set-shareholder',
            static::BILLS => 'constitution/bills',
            static::CURRENCY => 'constitution/currency',
        ];
        
        return '/work/bills/'.$views[$type];
    }
    
    public static function getCategory(int $type) : string
    {
        switch ($type) {
            case static::CREATE_AGENCY:
            case static::RENAME_AGENCY:
            case static::SET_AGENCY_LEADER:
                return Yii::t('app', 'Agencies');
            case static::POST_DESTIGNATION:
            case static::CREATE_POST:
            case static::POST_POWERS:
            case static::RENAME_POST:
            case static::DELETE_POST:
            case static::FIRE_FROM_POST:
                return Yii::t('app', 'Agency posts');
            case static::RENAME_REGION:
            case static::CHANGE_FLAG_REGION:
            case static::CHANGE_ANTHEM_REGION:
            case static::CREATE_REGION:
            case static::IMPLODE_REGIONS:
            case static::CHANGE_REGIONS_BORDER:
            case static::CHANGE_CAPITAL_REGION:
            case static::SET_REGION_LEADER:
                return Yii::t('app', 'Regions');
            case static::RENAME_CITY:
            case static::CHANGE_FLAG_CITY:
            case static::CHANGE_ANTHEM_CITY:
            case static::SET_CITY_LEADER:
                return Yii::t('app', 'Cities');
            case static::PARTIES_POLITIC:
            case static::MULTIPOST_POLITIC:
            case static::BUSINESS:
            case static::BILLS:
            case static::CURRENCY:
                return Yii::t('app', 'Constitution');
            case static::CREATE_DISTRICT:
            case static::IMPLODE_DISTRICTS:
            case static::CHANGE_DISTRICTS_BORDER:
                return Yii::t('app', 'Electoral districts');
            case static::COMPANY_CREATE:
            case static::LICENSE_RULE:
            case static::SET_SHAREHOLDER:
                return Yii::t('app', 'Economics');
            default:
                return Yii::t('app', 'Basic bill types');
        }
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className();
    }
    
    /**
     * 
     * @param Bill $bill
     * @return array
     */
    public function getDefaultData($bill)
    {
        return [];
    }
    
    /**
     * 
     * @param Bill $bill
     * @return string
     */
    public function renderFull($bill) : string
    {
        return $this->render($bill);
    }
    
    public static function isAvailable(State $state) : bool
    {
        return true;
    }
    
}
