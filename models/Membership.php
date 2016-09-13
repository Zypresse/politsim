<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * @property integer $userId
 * @property integer $partyId
 * @property integer $dateCreated
 * @property integer $dateApproved
 */
class Membership extends MyModel
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'partyId'], 'required'],
            [['userId', 'partyId', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
        ];
    }
    
    public function beforeSave($insert) {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
    
}
