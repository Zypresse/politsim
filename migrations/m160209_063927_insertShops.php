<?php

use yii\db\Migration,
    app\models\factories\proto\FactoryProto,
    app\models\factories\proto\FactoryProtoCategory,
    app\models\factories\proto\FactoryProtoWorker,
    app\models\factories\proto\FactoryProtoLicense,
    app\models\licenses\proto\LicenseProto;

class m160209_063927_insertShops extends Migration
{
    
    private $toSave = [];
    private $transaction;

    public function safeUp()
    {
                
        $cat = new FactoryProtoCategory([
            'name' => 'Магазины'
        ]);        
        if (!$cat->save()) {
            var_dump($cat->getErrors());
            return false;
        }        
        
        $licenseProto = new LicenseProto([
            'name' => "Розничная торговля",
            'code' => "retail"
        ]);
        if (!$licenseProto->save()) {
            var_dump($licenseProto->getErrors());
            return false;
        }
                
        $foodShop = new FactoryProto([
            'name' => 'Продуктовый магазин',
            'level' => FactoryProto::LEVEL_SHOP,
            'system_name' => 'food_shop',
            'category_id' => $cat->id,
            'can_build_npc' => 1,
            'build_cost' => 500,
            'class' => 'FoodShop'
        ]);
        if (!$foodShop->save()) {
            var_dump($foodShop->getErrors());
            return false;
        }
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $foodShop->id,
            'pop_class_id' => 9, // клерки
            'count' => 5
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $foodShop->id,
            'pop_class_id' => 1, // рабочие
            'count' => 4
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $foodShop->id,
            'pop_class_id' => 3, // силовики
            'count' => 1
        ]);
        
        $this->toSave[] = new FactoryProtoLicense([
            'factory_proto_id' => $foodShop->id,
            'license_proto_id' => $licenseProto->id
        ]);
        
        $dressShop = new FactoryProto([
            'name' => 'Магазин одежды',
            'level' => FactoryProto::LEVEL_SHOP,
            'system_name' => 'dress_shop',
            'category_id' => $cat->id,
            'can_build_npc' => 1,
            'build_cost' => 300,
            'class' => 'DressShop'
        ]);
        if (!$dressShop->save()) {
            var_dump($dressShop->getErrors());
            return false;
        }
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $dressShop->id,
            'pop_class_id' => 9, // клерки
            'count' => 6
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $dressShop->id,
            'pop_class_id' => 1, // рабочие
            'count' => 3
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $dressShop->id,
            'pop_class_id' => 3, // силовики
            'count' => 1
        ]);
        
        $this->toSave[] = new FactoryProtoLicense([
            'factory_proto_id' => $dressShop->id,
            'license_proto_id' => $licenseProto->id
        ]);
        
        $furnitureShop = new FactoryProto([
            'name' => 'Магазин мебели',
            'level' => FactoryProto::LEVEL_SHOP,
            'system_name' => 'furniture_shop',
            'category_id' => $cat->id,
            'can_build_npc' => 1,
            'build_cost' => 400,
            'class' => 'FurnitureShop'
        ]);
        if (!$furnitureShop->save()) {
            var_dump($furnitureShop->getErrors());
            return false;
        }
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $furnitureShop->id,
            'pop_class_id' => 9, // клерки
            'count' => 5
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $furnitureShop->id,
            'pop_class_id' => 1, // рабочие
            'count' => 6
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $furnitureShop->id,
            'pop_class_id' => 3, // силовики
            'count' => 1
        ]);
        
        $this->toSave[] = new FactoryProtoLicense([
            'factory_proto_id' => $furnitureShop->id,
            'license_proto_id' => $licenseProto->id
        ]);
        
        $bigShop = new FactoryProto([
            'name' => 'Универсальный магазин',
            'level' => FactoryProto::LEVEL_SHOP,
            'system_name' => 'big_shop',
            'category_id' => $cat->id,
            'can_build_npc' => 1,
            'build_cost' => 1000,
            'class' => 'BigShop'
        ]);
        if (!$bigShop->save()) {
            var_dump($bigShop->getErrors());
            return false;
        }
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $bigShop->id,
            'pop_class_id' => 9, // клерки
            'count' => 10
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $bigShop->id,
            'pop_class_id' => 1, // рабочие
            'count' => 10
        ]);
        
        $this->toSave[] = new FactoryProtoWorker([
            'proto_id' => $bigShop->id,
            'pop_class_id' => 3, // силовики
            'count' => 2
        ]);
                
        $this->toSave[] = new FactoryProtoLicense([
            'factory_proto_id' => $bigShop->id,
            'license_proto_id' => $licenseProto->id
        ]);
        
        
        return $this->saveAll();
        
    }
    
    private function saveAll()
    {
        foreach ($this->toSave as $model) {
            if (!$model->save()) {
                var_dump($model->getErrors());
                return false;
            }
        }
        return true;
    }

    public function safeDown()
    {
        $cat = FactoryProtoCategory::findBySql("name = 'Магазины'")->one();
        $this->delete('factories_prototypes_categories', 'id = :catId', [':catId' => $cat->id]);            
        
        $licenseProto = LicenseProto::findByCode('retail');
        $this->delete('licenses_prototypes', 'id = :licenseId', [':licenseId' => $licenseProto->id]);            
        
        $factories = FactoryProto::findBySql("class IN ('FoodShop', 'DressShop', 'FurnitureShop', 'BigShop')")->all();
        foreach ($factories as $factory) {
            /* @var $factory FactoryProto */
            $this->delete('factories_prototypes_workers', 'proto_id = :protoId', [':protoId' => $factory->id]);
            $this->delete('factories_prototypes_licenses', 'factory_proto_id = :protoId', [':protoId' => $factory->id]);
            $this->delete('factories_prototypes', 'id = :protoId', [':protoId' => $factory->id]);            
        }
        
    }

}
