<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $text
 */
class Notification extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'text'], 'required'],
            [['uid'], 'integer'],
            [['text'], 'string', 'max' => 1023]
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
            'text' => 'Text',
        ];
    }
}
