<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "dealings".
 *
 * @property integer $id
 * @property integer $from_uid
 * @property integer $to_uid
 * @property double $sum
 * @property string $items
 * @property integer $is_anonim
 * @property integer $is_secret
 * @property integer $time
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

    public static function getMyList($uid,$viewer_id)
    {
        $is_own = ($viewer_id === $uid);

        $dealings = static::find()->where("to_uid = {$uid} OR from_uid = {$uid}")->orderBy('time DESC')->all();
        foreach ($dealings as $i => $d) {
            // Some magic
            if (!((!$d->is_secret && !$d->is_anonim) || (($d->is_secret && $is_own) || ($d->is_anonim && $d->from_uid == $viewer_id && $is_own) || ($d->is_anonim && $d->to_uid == $viewer_id))))
                unset($dealings[$i]);
        }
        return $dealings;
    }
}
