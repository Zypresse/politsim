<?php

namespace app\models\factories\proto;

use app\models\objects\proto\UnmovableObjectProto,
    app\models\factories\proto\FactoryProtoCategory as Category,
    app\models\factories\proto\FactoryProtoKit as Kit,
    app\models\factories\proto\FactoryProtoWorker as Worker,
    app\models\factories\proto\FactoryProtoLicense as License,
    app\models\factories\Factory,
    app\models\Region,
    app\models\Holding;

/**
 * Тип фабрики. Таблица "factories_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property string $system_name
 * @property integer $category_id
 * @property integer $can_build_npc
 * @property double $build_cost
 * @property string $class_name
 * 
 * @property Category $category Категория фабрик
 * @property Kit[] $resurses Набор всех ресурсов
 * @property Kit[] $export Производимый набор ресурсов
 * @property Kit[] $import Потребляемый набор ресурсов
 * @property Kit[] $used Используемый набор ресурсов
 * @property Worker[] $workers Используемые наборы рабочих
 * @property License[] $licenses Необходимые лицензии
 */
class FactoryProto extends UnmovableObjectProto
{

    public static function instantiate($row)
    {
        $className = "app\\models\\factories\\proto\\types\\{$row['class_name']}";
        return new $className;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level', 'system_name', 'category_id', 'build_cost', 'class_name'], 'required'],
            [['name', 'system_name', 'class_name'], 'string'],
            [['level', 'category_id', 'can_build_npc'], 'integer'],
            [['build_cost'],'number'],
            [['build_cost'], 'number'],
            [['class_name'], 'unique']
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
            'level' => 'Level',
            'system_name' => 'System Name',
            'category_id' => 'Category ID',
            'can_build_npc' => 'Can Build Npc',
            'build_cost' => 'Build Cost',
            'class_name' => 'Class Name',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), array('id' => 'category_id'));
    }

    public function getResurses()
    {
        return $this->hasMany(Kit::className(), array('factory_proto_id' => 'id'));
    }

    public function getExport()
    {
        return $this->hasMany(Kit::className(), array('factory_proto_id' => 'id'))->where(['direction' => 2]);
    }

    public function getImport()
    {
        return $this->hasMany(Kit::className(), array('factory_proto_id' => 'id'))->where(['direction' => 1]);
    }

    public function getUsed()
    {
        return $this->hasMany(Kit::className(), array('factory_proto_id' => 'id'))->where(['direction' => 3]);
    }

    public function getWorkers()
    {
        return $this->hasMany(Worker::className(), array('proto_id' => 'id'));
    }    

    public function getSumNeedWorkers()
    {
        return intval($this->hasMany(Worker::className(), array('proto_id' => 'id'))->sum("count"));
    }    
    
    public function getLicenses()
    {
        return $this->hasMany(License::className(), ['id' => 'license_proto_id'])
                ->viaTable('factory_prototypes_licenses', ['factory_proto_id' => 'id']);
    }
    
    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return 1;
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
     * @return Factory 
     */
    public function startBuild(Region $region, Holding $holding, $name, $size = 1)
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
