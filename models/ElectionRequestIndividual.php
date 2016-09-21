<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Индивидуальная заявка на выборы
 *
 * @property integer $electionId
 * @property integer $userId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property User $user
 * 
 */
class ElectionRequestIndividual extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elections-requests-individual';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'userId', 'variant'], 'required'],
            [['electionId', 'userId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['electionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['electionId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }
    
    public function beforeSave($insert)
    {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getElection()
    {
        return $this->hasOne(Election::className(), ['id' => 'electionId']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function primaryKey()
    {
        return ['electionId', 'variant'];
    }
    
}
