<?php

namespace app\models;

use app\components\MyModel,
    app\models\User;

/**
 * This is the model class for table "invites".
 *
 * @property integer $id
 * @property string $code
 * @property integer $userId
 * @property integer $dateActivated
 * 
 * @property User $user
 */
class Invite extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string', 'max' => 255],
            [['userId', 'dateActivated'], 'integer', 'min' => 0],
            [['code', 'userId'], 'unique']
        ];
    }
    
    /**
     * Использован ли инвайт
     * @return boolean
     */
    public function isUsed()
    {
        return !!$this->userId;
    }
    
    /**
     * Активировать инвайт
     */
    public function activateUser(User &$user)
    {
        $this->userId = $user->id;
        $this->dateActivated = time();
        $this->save();
        
        $user->isInvited = true;
        $user->save();
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }

}
