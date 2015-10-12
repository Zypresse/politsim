<?php

namespace app\models;

use app\components\MyModel;

/**
 * Значки. Таблица "medales".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $uid_vk
 * @property integer $type
 * 
 * @property \app\models\MedaleProto $proto Тип значка
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
            'id'     => 'ID',
            'uid'    => 'Uid',
            'uid_vk' => 'Uid Vk',
            'type'   => 'Type',
        ];
    }

    public function getProto()
    {
        return $this->hasOne('app\models\MedaleProto', array('id' => 'type'));
    }

}
