<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "bills".
 *
 * @property integer $id
 * @property integer $bill_type
 * @property integer $creator
 * @property integer $created
 * @property integer $vote_ended
 * @property integer $accepted
 */
class Bill extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_type', 'creator', 'created', 'vote_ended'], 'required'],
            [['bill_type', 'creator', 'created', 'vote_ended', 'accepted'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bill_type' => 'Bill Type',
            'creator' => 'Creator',
            'created' => 'Created',
            'vote_ended' => 'Vote Ended',
            'accepted' => 'Accepted',
            'data' => 'Данные',
        ];
    }

    public function getType()
    {
        return $this->hasOne('app\models\BillType', array('id' => 'bill_type'));
    }
    public function getCreatorpost()
    {
        return $this->hasOne('app\models\Post', array('id' => 'creator'));
    }
}
