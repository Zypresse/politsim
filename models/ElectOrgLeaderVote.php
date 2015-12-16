<?php

namespace app\models;

use app\components\MyModel;

/**
 * Голос на выборах лидера организации голосованием членов. Таблица "elects_orgleader_votes".
 *
 * @property integer $id
 * @property integer $request_id
 * @property integer $post_id
 */
class ElectOrgLeaderVote extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elects_orgleader_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'post_id'], 'required'],
            [['request_id', 'post_id'], 'integer'],
            [['request_id', 'post_id'], 'unique', 'targetAttribute' => ['request_id', 'post_id'], 'message' => 'The combination of Request ID and Post ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request ID',
            'post_id' => 'Post ID',
        ];
    }
}