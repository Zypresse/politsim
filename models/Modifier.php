<?php

namespace app\models;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Специальный модификатор юзера (баффы напр.)
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $userId
 * @property integer $dateReceiving
 * @property integer $dateExpired
 * 
 * @property string $name
 * @property string $icon
 * @property integer $fame
 * @property integer $trust
 * @property integer $success
 * 
 * @property User $user
 * @property ModifierProto $proto
 * 
 */
class Modifier extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modifiers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'userId'], 'required'],
            [['protoId', 'userId', 'dateReceiving', 'dateExpired'], 'integer', 'min' => 0],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'userId' => Yii::t('app', 'User ID'),
            'dateReceiving' => Yii::t('app', 'Date Receiving'),
            'dateExpired' => Yii::t('app', 'Date Expired'),
        ];
    }
    
    public function beforeSave($insert)
    {
        
        if ($insert) {
            if (!$this->dateReceiving) {
                $this->dateReceiving = time();
            }
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    private $_proto = null;
    public function getProto()
    {
        if (is_null($this->_proto)) {
            $this->_proto = ModifierProto::findOne($this->protoId);
        }
        return $this->_proto;
    }
    
    public function getName()
    {
        return $this->proto->name;
    }
    
    public function getIcon()
    {
        return $this->proto->icon;
    }
    
    public function getFame()
    {
        return $this->proto->fame;
    }
    
    public function getTrust()
    {
        return $this->proto->trust;
    }
    
    public function getSuccess()
    {
        return $this->proto->success;
    }
    
}
