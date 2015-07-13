<?php

namespace app\models;

use app\components\MyModel;

/**
 * Сделка между игроками. Таблица "dealings".
 *
 * @property integer $id
 * @property integer $from_unnp ID отправителя
 * @property integer $to_unnp ID получателя
 * @property double $sum Сумма (сколько отправил отправитель)
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
            [['from_unnp', 'to_unnp'], 'required'],
            [['from_unnp', 'to_unnp', 'is_anonim', 'is_secret', 'time'], 'integer'],
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
            'id'        => 'ID',
            'from_unnp'  => 'From UNNP',
            'to_unnp'    => 'To UNNP',
            'sum'       => 'Sum',
            'items'     => 'Items',
            'is_anonim' => 'Is Anonim',
            'is_secret' => 'Is Secret',
            'time'      => 'Time',
        ];
    }

    public function getSender()
    {
        return Unnp::findByPk($this->from_unnp)->getMaster();
    }

    public function getRecipient()
    {
        return Unnp::findByPk($this->to_unnp)->getMaster();
    }

    /**
     * Получить видимый для игрока viewer_id список сделок игрока uid
     * @param integer $uid
     * @param integer $viewer_id
     * @return Dealing[]
     */
    public static function getMyList($uid, $viewer_id = false)
    {
        if ($viewer_id === false)
            $viewer_id = $uid;
        $is_own    = ($viewer_id === $uid);
        
        $user = User::findByPk($uid);

        $dealings = static::find()->where("(to_unnp = {$user->unnp} OR from_unnp = {$user->unnp}) AND time>0")->orderBy('time DESC')->limit(30)->all();
        foreach ($dealings as $i => $d) {
            // Some magic
            if (!(((!$d->is_secret) || ($d->is_secret && $is_own)) && ((!$d->is_anonim) || ($d->to_unnp === $user->unnp)))) {
                unset($dealings[$i]);
            }
        }
        return $dealings;
    }

}
