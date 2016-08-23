<?php

namespace app\models;

use app\components\MyModel;

/**
 * Прототип налога. Таблица "taxes_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property double $default_value
 */
class TaxProto extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taxes_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['default_value'], 'number']
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
            'default_value' => 'Default Value',
        ];
    }
}
