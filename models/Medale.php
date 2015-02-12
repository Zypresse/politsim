<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "medales".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $uid_vk
 * @property integer $type
 */
class Medale extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type'], 'required'],
            [['uid', 'uid_vk', 'type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'uid_vk' => 'Uid Vk',
            'type' => 'Type',
        ];
    }


    public function getMedaletype()
    {
        return $this->hasOne('app\models\MedaleType', array('id' => 'type'));
    }
}
