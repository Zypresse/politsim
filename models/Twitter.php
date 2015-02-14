<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "twitter".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $text
 * @property integer $retweets
 * @property integer $date
 * @property integer $original
 */
class Twitter extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'twitter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'text', 'retweets', 'date'], 'required'],
            [['uid', 'retweets', 'date', 'original'], 'integer'],
            [['text'], 'string', 'max' => 140]
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
            'retweets' => 'Retweets',
            'date' => 'Date',
            'original' => 'uid юзера, автора оригинального твита (это ретвит)',
        ];
    }


    public function getUser()
    {
        return $this->hasOne('app\models\User', array('id' => 'uid'));
    }
    public function getOriginalUser()
    {
        return $this->hasOne('app\models\User', array('id' => 'original'));
    }
}
