<?php

namespace app\models;

use app\components\MyModel;

/**
 * Твит. Таблица "twitter".
 *
 * @property integer $id
 * @property integer $uid ID юзера
 * @property string $text Текст твита
 * @property integer $retweets Число ретвитов
 * @property integer $date Дата публикации
 * @property integer $original Если является ретвитом, то здесь ID юзера, запостившего оригинал
 * 
 * @property \app\models\User $user Автор твита
 * @property \app\models\User $user Автор оригинального твита (если это ретвит)
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
            [['text'], 'string', 'max' => 200]
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
