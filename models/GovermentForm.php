<?php
// @todo Вроде не используется, удалить
namespace app\models;

use app\components\MyModel;

/**
 * Форма правления. Таблица "goverment_forms".
 *
 * @property integer $id
 * @property string $name
 * @property integer $allow_register_parties
 */
class GovermentForm extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goverment_forms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['allow_register_parties'], 'integer'],
            [['name'], 'string', 'max' => 100]
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
            'allow_register_parties' => 'Allow Register Parties',
        ];
    }
}
