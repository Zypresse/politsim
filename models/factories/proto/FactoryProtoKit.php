<?php

namespace app\models\factories\proto;

use app\components\MyModel,
    app\models\factories\proto\FactoryProto,
    app\models\resurses\proto\ResurseProto;

/**
 * Набор ресурсов для работы завода. Таблица "factories_prototypes_kits".
 *
 * @property integer $id
 * @property integer $resurse_proto_id
 * @property integer $count
 * @property integer $direction Направление: 1 - потребляемые, 2 - производимые, 3 - блокируемые
 * @property integer $factory_proto_id
 * 
 * @property ResurseProto $resurseProto
 * @property FactoryProto $proto
 */
class FactoryProtoKit extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_prototypes_kits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resurse_proto_id', 'count', 'direction', 'factory_proto_id'], 'required'],
            [['resurse_proto_id', 'count', 'direction', 'factory_proto_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'resurse_proto_id' => 'Resurse ID',
            'count'      => 'Count',
            'direction'  => '1 — in, 2 — out, 3 — blocked',
            'factory_proto_id'    => 'Type ID',
        ];
    }

    public function getResurseProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'resurse_proto_id'));
    }

    public function getProto()
    {
        return $this->hasOne(FactoryProto::className(), array('id' => 'factory_proto_id'));
    }

}
