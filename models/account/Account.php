<?php

namespace app\models\account;

use Yii,
    app\models\base\MyActiveRecord,
    app\models\User;

/**
 * Аккаунт в соцсетях, привязанный к юзеру
 *
 * @property integer $id
 * @property integer $userId
 * @property string $sourceType
 * @property string $sourceId
 * 
 * @property User $user
 */
class Account extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'sourceType', 'sourceId'], 'required'],
            [['userId'], 'integer', 'min' => 0],
            [['sourceType'], 'integer', 'min' => 0, 'max' => 9],
            [['sourceId'], 'string', 'max' => 255]
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
    
    /**
     * Регистрирует нового юзера и новый аккаунт
     * @param integer $sourceType
     * @param array $attributes
     * @return Auth|User
     */
    public static function signUp($sourceType, $attributes)
    {
        $user = new User();
        
        $user->updateLastLogin();
        self::updateUserInfo($user, $sourceType, $attributes);
        
        $transaction = $user->getDb()->beginTransaction();
        if ($user->save()) {
            $account = new self([
                'userId' => $user->id,
                'sourceType' => $sourceType,
                'sourceId' => (string)(isset($attributes['id'])?$attributes['id']:$attributes['uid']),
            ]);
            if ($account->save()) {
                $transaction->commit();
                Yii::$app->user->login($user);
            }
            return $account;
        } else {
            return $user;
        }
    }
    
    /**
     * Обновляет поля юзера из данных апи соцсетей
     * @param User $user
     * @param integer $sourceType
     * @param array $attributes
     * @param boolean $save
     */
    public static function updateUserInfo(&$user, $sourceType, $attributes, $save = false)
    {
        
        
        $user->load(AccountSource::getParams($sourceType, $attributes), '');
        
        if ($save) {
            return $user->save();
        }
        
        return true;
    }
    
}
