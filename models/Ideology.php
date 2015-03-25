<?php

namespace app\models;

use app\components\MyModel;

/**
 * Идеология. Таблица "ideologies".
 *
 * @property integer $id
 * @property string $name
 * @property integer $d Уровень «правости» 0 — коммунизм, 50 социал-либерализм, 100 — фашизм
 */
class Ideology extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ideologies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'd'], 'required'],
            [['d'], 'integer'],
            [['name'], 'string', 'max' => 300]
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
            'd'    => 'Уровень \"правости\" 0 — коммунизм, 50 социал-либерализм, 100 — фашизм',
        ];
    }

}
