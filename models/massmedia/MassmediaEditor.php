<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\Massmedia,
    app\models\User;

/**
 * This is the model class for table "massmedia_editors".
 *
 * @property integer $userId
 * @property integer $massmediaId
 * @property integer $rules
 * @property integer $posts
 * @property integer $rating
 * @property boolean $hide
 * @property string $customName
 * 
 * @property User $user
 * @property Massmedia $massmedia
 * 
 */
class MassmediaEditor extends MyModel
{
    
    /**
     * Публикация статей
     */
    const RULE_POST = 1;
    
    /**
     * Удаление любых статей
     */
    const RULE_DELETE_POSTS = 16;
    
    /**
     * Удаление любых комментариев
     */
    const RULE_DELETE_COMMENTS = 32;
    
    /**
     * Назначение редакторов
     */
    const RULE_SET_EDITORS = 128;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia_editors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'massmediaId', 'rules'], 'required'],
            [['userId', 'massmediaId', 'rules', 'posts', 'rating'], 'integer'],
            [['customName'], 'string'],
            [['hide'], 'boolean'],            
            [['userId', 'massmediaId'], 'unique', 'targetAttribute' => ['userId', 'massmediaId'], 'message' => 'The combination of User ID and Massmedia ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'massmediaId' => 'Massmedia ID',
            'rules' => 'Rules',
            'posts' => 'Posts', 
            'rating' => 'Rating', 
            'hide' => 'Hide', 
            'customName' => 'Custom Name', 
        ];
    }
        
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
        
    public function getMassmedia()
    {
        return $this->hasOne(Massmedia::className(), array('id' => 'massmediaId'));
    }
    
    public static function primaryKey()
    {
        return ['userId', 'massmediaId'];
    }
    
    public static function generateRoot()
    {
        return new self([
            'rules' => 255
        ]);
    }
    
    /**
     * 
     * @param integer $permission
     * @return boolean
     */
    public function isHavePermission($permission)
    {
        return !!($this->rules & $permission);
    }
}
