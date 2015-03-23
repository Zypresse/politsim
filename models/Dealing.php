<?php

namespace app\models;

use app\components\MyModel;

/**
 * Сделка между игроками. Таблица "dealings".
 *
 * @property integer $id
 * @property integer $from_uid ID отправителя
 * @property integer $to_uid ID получателя
 * @property double $sum Переданная сумма денег
 * @property string $items Список вещей в JSON
 * @property integer $is_anonim Является ли сделка анонимной
 * @property integer $is_secret Является ли сделка тайной
 * @property integer $time Время совершения сделки (-1 для непринятой)
 * 
 * @property User $sender Отправитель
 * @property User $recipient Получатель
 */
class Dealing extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_uid', 'to_uid'], 'required'],
            [['from_uid', 'to_uid', 'is_anonim', 'is_secret', 'time'], 'integer'],
            [['sum'], 'number'],
            [['items'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_uid' => 'From Uid',
            'to_uid' => 'To Uid',
            'sum' => 'Sum',
            'items' => 'Items',
            'is_anonim' => 'Is Anonim',
            'is_secret' => 'Is Secret',
            'time' => 'Time',
        ];
    }

    public function getSender()
    {
        return $this->hasOne('app\models\User', array('id' => 'from_uid'));
    }

    public function getRecipient()
    {
        return $this->hasOne('app\models\User', array('id' => 'to_uid'));
    }

    /**
     * Получить видимый для игрока viewer_id список сделок игрока uid
     * @param integer $uid
     * @param integer $viewer_id
     * @return Dealing[]
     */
    public static function getMyList($uid,$viewer_id = false)
    {
        if ($viewer_id === false) $viewer_id = $uid;
        $is_own = ($viewer_id === $uid);

        $dealings = static::find()->where("(to_uid = {$uid} OR from_uid = {$uid}) AND time>0")->orderBy('time DESC')->all();
        foreach ($dealings as $i => $d) {
            // Some magic
            if (!(((!$d->is_secret) || ($d->is_secret && $is_own)) && ((!$d->is_anonim) || ($d->to_uid === $uid))))
                unset($dealings[$i]);
        }
        return $dealings;
    }
}
