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
     * Подтвердить гражданство
     * @param boolean $save
     */
    public function approve($save = true)
    {
        $this->dateApproved = time();
        if ($save) {
            if ($this->save()) {
//                Yii::$app->notificator->citizenshipApprouved($this->userId, $this->state);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 
     * @param boolean $self
     * @return boolean
     */
    public function fire($self = false)
    {
        if ($this->delete()) {
//            $userId = $this->userId;
//            $state = $this->state;
//            Yii::$app->notificator->citizenshipLost($userId, $state, $self);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @return boolean
     */
    public function fireSelf()
    {
        return $this->fire(true);
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
