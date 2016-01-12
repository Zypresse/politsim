<?php

namespace app\models;

use app\components\MyModel,
    app\models\User;

/**
 * Твит. Таблица "tweets".
 *
 * @property integer $id
 * @property integer $uid ID юзера
 * @property string $text Текст твита
 * @property integer $retweets Число ретвитов
 * @property integer $date Дата публикации
 * @property integer $original Если является ретвитом, то здесь ID юзера, запостившего оригинал
 * 
 * @property User $user Автор твита
 * @property User $user Автор оригинального твита (если это ретвит)
 */
class Twitter extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tweets';
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
            'id'       => 'ID',
            'uid'      => 'Uid',
            'text'     => 'Text',
            'retweets' => 'Retweets',
            'date'     => 'Date',
            'original' => 'uid юзера, автора оригинального твита (это ретвит)',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'uid'));
    }

    public function getOriginalUser()
    {
        return $this->hasOne(User::className(), array('id' => 'original'));
    }

}
