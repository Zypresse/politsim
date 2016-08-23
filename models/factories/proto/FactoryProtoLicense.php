<?php

namespace app\models\factories\proto;

use app\components\MyModel;

/**
 * This is the model class for table "factories_prototypes_licenses".
 *
 * @property integer $id
 * @property integer $factory_proto_id
 * @property integer $license_proto_id
 */
class FactoryProtoLicense extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_prototypes_licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_proto_id', 'license_proto_id'], 'required'],
            [['factory_proto_id', 'license_proto_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_proto_id' => 'Factory Proto ID',
            'license_proto_id' => 'License Proto ID',
        ];
    }
}
