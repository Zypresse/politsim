<?php

namespace app\models;

use Yii,
    app\components\MyModel,
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
class Account extends MyModel
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
        $user = new User([
            'cityId' => 1
        ]);
        
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
        switch ($sourceType) {
            case 1: //google
                $params = [
                    'name' => $attributes['displayName'],
                    'genderId' => User::stringGenderToSex($attributes['gender']),
                    'avatar' => $attributes['image']['url'],
                    'avatarBig' => preg_replace("/sz=50/", "/sz=400", $attributes['image']['url'])               
                ];
                break;
            case 2: //facebook
                $params = [
                    'name' => $attributes['name'],
                    'genderId' => User::stringGenderToSex($attributes['gender']),
                    'avatar' => "http://graph.facebook.com/{$attributes['id']}/picture",
                    'avatarBig' => "http://graph.facebook.com/{$attributes['id']}/picture?width=400&height=800"
                ];
                break;
            case 3: // vkontakte\vkapp
                $params = [
                    'name' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                    'genderId' => intval($attributes['sex']),
                    'avatar' => $attributes['photo_50'],
                    'avatarBig' => (isset($attributes['photo_400_orig'])) ? $attributes['photo_400_orig'] : $attributes['photo_big']
                ];
                break;
            default:
                return false;
        }
        
        $user->load($params, '');
        
        if ($save) {
            return $user->save();
        }
        
        return true;
    }
    
}
