<?php

namespace app\models\licenses\proto;

use app\components\MyModel;

/**
 * Типы лицензий (напр. банковское дело или добыча нефти). Таблица "licenses_prototypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class LicenseProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licenses_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique']
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
            'code' => 'Code',
        ];
    }

}
