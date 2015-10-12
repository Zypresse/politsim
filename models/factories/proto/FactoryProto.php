<?php

namespace app\models\factories\proto;

use app\components\MyModel;

/**
 * Тип фабрики. Таблица "factory_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property string $system_name
 * @property integer $category_id
 * @property integer $can_build_npc
 * @property float $build_cost
 * 
 * @property FactoryProtoCategory $category Категория фабрик
 * @property FactoryProtoKit[] $resurses Набор всех ресурсов
 * @property FactoryProtoKit[] $export Производимый набор ресурсов
 * @property FactoryProtoKit[] $import Потребляемый набор ресурсов
 * @property FactoryProtoKit[] $used Используемый набор ресурсов
 * @property FactoryProtoWorker[] $workers Используемые наборы рабочих
 * @property FactoryProtoLicenses[] $licenses Необходимые лицензии
 */
class FactoryProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level', 'system_name', 'category_id'], 'required'],
            [['level', 'category_id', 'can_build_npc'], 'integer'],
            [['build_cost'],'number'],
            [['name', 'system_name'], 'string', 'max' => 255]
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
            'level'         => 'Level',
            'system_name'   => 'System Name',
            'category_id'   => 'Category ID',
            'can_build_npc' => 'Can Build Npc',
            'build_cost'    => 'Build cost',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne('app\models\factories\proto\FactoryProtoCategory', array('id' => 'category_id'));
    }

    public function getResurses()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoKit', array('factory_proto_id' => 'id'));
    }

    public function getExport()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoKit', array('factory_proto_id' => 'id'))->where(['direction' => 2]);
    }

    public function getImport()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoKit', array('factory_proto_id' => 'id'))->where(['direction' => 1]);
    }

    public function getUsed()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoKit', array('factory_proto_id' => 'id'))->where(['direction' => 3]);
    }

    public function getWorkers()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoWorker', array('proto_id' => 'id'));
    }    

    public function getSumNeedWorkers()
    {
        return intval($this->hasMany('app\models\factories\proto\FactoryProtoWorker', array('proto_id' => 'id'))->sum("count"));
    }    
    
    public function getLicenses()
    {
        return $this->hasMany('app\models\factories\proto\FactoryProtoLicense', ['id' => 'license_proto_id'])
                ->viaTable('factory_prototypes_licenses', ['factory_proto_id' => 'id']);
    }
    
    /**
     * Добывающие ресурсы 0 уровня
     */
    const LEVEL_DIG = 0;
    
    /**
     * Перерабатывающие ресурсы 0 и 1 уровней в ресурсы 1 и 2 уровней
     */
    const LEVEL_FACTORY = 1;
    
    /**
     * Перерабатывающие отходы (ресурсы 2 уровня)
     */
    const LEVEL_DUMPWORKER = 2;
    
    /**
     * Продающие ресурсы населению
     */
    const LEVEL_SHOP = 3;
    
    /**
     * Оказывающие услуги населению (банки, салоны красоты и т.п.)
     */
    const LEVEL_SALOON = 4;
    
    /**
     * Не ведущие прямой деятельности (офисы)
     */
    const LEVEL_NOTWORKER = 5;
    
    /**
     * Склады
     */
    const LEVEL_STORE = 6;
    
    /**
     * Электростанции
     */
    const LEVEL_POWERPLANT = 7;
    
    /**
     * Фабрика для создания фабрики текущего типа
     * @param \app\models\Region $region
     * @param \app\models\Holding $holding
     * @param string $name
     * @param integer $size
     * 
     * @return \app\models\factories\Factory 
     */
    public function startBuild(\app\models\Region $region, \app\models\Holding $holding, $name, $size = 1)
    {
        if ($size < 1) {
            $size = 1;
        } elseif ($size > 127) {
            $size = 127;
        }
        if (is_null($region)) {
            throw new \yii\base\Exception("Undefined region");
        }
        if (is_null($holding)) {
            throw new \yii\base\Exception("Undefined holding");
        }
        
        if ($holding->capital < $this->build_cost) {
            // Недостаточно денег
            return null;
        }
        
        $factory = new Factory();
        $factory->type_id = $this->id;
        $factory->region_id = $region->id;
        $factory->holding_id = $holding->id;
        $factory->status = -1;
        $factory->size = $size;
        $factory->name = $name;
        
        if ($factory->save()) {
            
            $holding->balance -= $this->build_cost;
            $holding->save();
            
            return $factory;
        } else {
            
            var_dump($factory->getErrors());
            return null;
        }
    }

}
