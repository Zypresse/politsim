<?php

namespace app\models\government;

use Yii;
use app\models\auth\User;
use app\models\government\State;
use app\models\base\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Гражданство
 *
 * @property integer $userId
 * @property integer $stateId
 * @property integer $dateCreated
 * @property integer $dateApproved
 * 
 * @property User $user
 * @property State $state
 */
class Citizenship extends ActiveRecord
{

    use \app\models\base\traits\MembershipTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'citizenships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'stateId'], 'required'],
            [['userId', 'stateId', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
            [['userId', 'stateId'], 'unique', 'targetAttribute' => ['userId', 'stateId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    [
		'class' => TimestampBehavior::className(),
		'createdAtAttribute' => 'dateCreated',
		'updatedAtAttribute' => false,
	    ],
	];
    }

    public static function primaryKey()
    {
        return ['userId', 'stateId'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::class, ['id' => 'stateId']);
    }
    
    /**
     * 
     * @param integer $userId
     * @return \yii\db\ActiveQuery
     */
    public static function findByUser(int $userId)
    {
        return self::find()->andWhere(['userId' => $userId]);
    }

}
