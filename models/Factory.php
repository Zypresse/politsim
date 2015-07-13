<?php

namespace app\models;

use app\components\NalogPayer,
    app\models\Unnp;

/**
 * Фабрика/завод/сх-предприятие. Таблица "factories".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $builded
 * @property integer $holding_id
 * @property integer $region_id
 * @property integer $status Статус работы: -1 - unbuilded, -2 - build stopped, 0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped, 5 - not enought workers, 6 - not have license
 * @property string $name
 * @property integer $size
 * @property integer $manager_uid
 * 
 * @property FactoryType $type Тип фабрики
 * @property Holding $holding Компания-владелец
 * @property Region $region Регион, в котором она находится
 * @property User $manager Управляющий
 * @property Population[] $workers Рабочие
 * @property FactoryWorkerSalary[] $salaries Установленные зарплаты рабочих
 * @property FactoryStorage[] $storages Ресурсы на складе
 */
class Factory extends NalogPayer
{
    /**
     * Строится
     */
    const STATUS_UNBUILDED = -1;

    /**
     * Строительство остановлено
     */
    const STATUS_BUILD_STOPPED = -2;

    /**
     * Состояние не определено
     */
    const STATUS_UNDEFINED = 0;

    /**
     * Работает
     */
    const STATUS_ACTIVE = 1;

    /**
     * Работа остановлена штатно
     */
    const STATUS_STOPPED = 2;

    /**
     * Работа остановлена автоматически -- на складе нет ресурсов для работы
     */
    const STATUS_NOT_ENOUGHT_RESURSES = 3;

    /**
     * Работа остановленна автоматически
     */
    const STATUS_AUTOSTOPPED = 4;

    /**
     * Работа остановлена автоматически -- недостаточно рабочих
     */
    const STATUS_NOT_ENOUGHT_WORKERS = 5;

    /**
     * Работа остановлена автоматически -- нет необходимой лицензии
     */
    const STATUS_HAVE_NOT_LICENSE = 6;

    protected function getUnnpType()
    {
        return Unnp::TYPE_FACTORY;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'builded', 'name'], 'required'],
            [['type_id', 'builded', 'holding_id', 'region_id', 'status', 'size', 'manager_uid'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'type_id'    => 'Type ID',
            'builded'    => 'Builded',
            'holding_id' => 'Holding ID',
            'region_id'  => 'Region ID',
            'status'     => 'Статус работы',
            'name'       => 'Name',
            'size'       => 'Size',
            'manager_uid'=> 'Manager Uid',
        ];
    }

    public function getType()
    {
        return $this->hasOne('app\models\FactoryType', array('id' => 'type_id'));
    }

    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }

    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }

    public function getManager()
    {
        return $this->hasOne('app\models\User', array('id' => 'manager_uid'));
    }
    
    public function getWorkers()
    {
        return $this->hasMany('app\models\Population', array('factory_id' => 'id'));
    }
    
    public function getSalaries()
    {
        return $this->hasMany('app\models\FactoryWorkersSalary', array('factory_id' => 'id'));
    }
    
    public function getVacansies()
    {
        return $this->hasMany('app\models\Vacansy', array('factory_id' => 'id'));
    }
    
    public function getStorages()
    {
        return $this->hasMany('app\models\FactoryStorage', array('factory_id' => 'id'));
    }
    
    public function getStatusName()
    {
        $names = [
            -2 => 'Строительство прекращено',
            -1 => 'Идёт строительство',
            0 => 'Неизвестен',
            1 => 'Работает',
            2 => 'Работа остановлена',
            3 => 'Работа остановлена по причине нехватки ресурсов',
            4 => 'Работа остановлена автоматически',
            5 => 'Работа остановлена по причине нехватки работников',
            6 => 'Работа остановлена по причине отстутствия необходимой лицензии'
        ];
        
        return $names[$this->status];
    }

    public function storageSize($resurse_id)
    {
        $kit = FactoryKit::find()->where(['resurse_id' => $resurse_id, 'type_id' => $this->type_id])->one();
        if ($kit) {
            return $this->size*24*$kit->count;
        } else {
            return 0;
        }
    }

}
