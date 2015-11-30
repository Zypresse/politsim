<?php

namespace app\models;

use app\components\MyModel,
    app\models\Stock,
    app\models\factories\Factory;

/**
 * Сделка между игроками. Таблица "dealings".
 *
 * @property integer $id
 * @property integer $proto_id ID типа сделки
 * @property integer $from_unnp ID отправителя
 * @property integer $to_unnp ID получателя
 * @property double $sum Сумма (сколько отправил отправитель)
 * @property string $items Список вещей в JSON
 * @property integer $is_anonim Является ли сделка анонимной
 * @property integer $is_secret Является ли сделка тайной
 * @property integer $time Время совершения сделки (-1 для непринятой)
 * 
 * @property \app\components\NalogPayer $sender Отправитель
 * @property \app\components\NalogPayer $recipient Получатель
 * @property DealingProto $proto Тип сделки
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
            [['from_unnp', 'to_unnp', 'is_anonim', 'is_secret', 'time', 'proto_id'], 'integer'],
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
        if ($viewer_id === false) {
            $viewer_id = $uid;
        }
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

    /**
     * 
     */
    public function accept()
    {
        
        $this->time = time();
        $this->save();

        if ($this->sum) {
            $this->recipient->changeBalance($this->sum);
            $this->sender->changeBalance(-1*$this->sum);
        }

        $items = json_decode($this->items, true);
        if (is_array($items)) {
            foreach ($items as $item) {
                switch ($item['type']) {
                    case "stock":
                        $stock = Stock::find()->where(['holding_id' => $item['holding_id'], 'unnp' => $this->sender->unnp])->one();
                        $recStock = Stock::findOrCreate(['holding_id' => $item['holding_id'], 'unnp' => $this->recipient->unnp], false, ['count' => 0]);

                        $stock->count -= $item['count'];
                        $recStock->count += $item['count'];

                        if ($stock->count > 0) {
                            $stock->save();
                        } else {
                            $stock->delete();
                        }
                        $recStock->save();
                        break;
                    case "factory":
                        $factory = Factory::findByPk($item['factory_id']);
                        
                        if ($this->recipient->getUnnpType() === Unnp::TYPE_HOLDING) {
                            $factory->holding_id = $this->recipient->id;
                            $factory->save();
                        }
                        break;
                    case "resurse":
                        $this->sender->delFromStorage($item['proto_id'],$item['count']);
                        $this->recipient->pushToStorage($item['proto_id'],$item['count']);
                        break;
                }
            }
        }
    }
    
}
