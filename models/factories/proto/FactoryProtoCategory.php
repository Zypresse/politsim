<?php

namespace app\models\factories\proto;

use app\components\MyModel,
    app\models\factories\proto\FactoryProto;

/**
 * Категория фабрик. Таблица "factories_prototypes_categories".
 *
 * @property integer $id
 * @property string $name
 * 
 * @property FactoryProto[] $protos
 */
class FactoryProtoCategory extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_prototypes_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }
    
    public function getProtos()
    {
        return $this->hasMany(FactoryProto::className(), array('category_id' => 'id'));
    }

}
