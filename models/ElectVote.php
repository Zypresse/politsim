<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "elect_votes".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $request_id
 */
class ElectVote extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elect_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'request_id'], 'required'],
            [['uid', 'request_id'], 'integer'],
            [['uid', 'request_id'], 'unique', 'targetAttribute' => ['uid', 'request_id'], 'message' => 'The combination of Uid and Request ID has already been taken.']
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
            'request_id' => 'Request ID',
        ];
    }
}
