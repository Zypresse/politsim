<?php

namespace app\models\bills\proto;

use app\components\MyModel;

/**
 * Поле типа законопроекта. Таблица "bills_prototypes_fields".
 *
 * @property integer $proto_id
 * @property string $name
 * @property string $system_name
 * @property string $type
 */
class BillProtoField extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills_prototypes_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proto_id', 'name', 'system_name', 'type'], 'required'],
            [['proto_id'], 'integer'],
            [['name'], 'string', 'max' => 1000],
            [['system_name', 'type'], 'string', 'max' => 255]/* ,
              [['system_name'], 'unique'] */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'proto_id'     => 'Bill ID',
            'name'        => 'Name',
            'system_name' => 'System Name',
            'type'        => 'Type',
        ];
    }

}
