<?php

namespace app\models\politics;

use Yii;
use app\models\base\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\auth\User;

/**
 * This is the model class for table "organizationsMemberships".
 *
 * @property integer $userId
 * @property integer $orgId
 * @property integer $dateCreated
 * @property integer $dateApproved
 *
 * @property Organization $org
 * @property User $user
 */
class OrganizationMembership extends ActiveRecord
{
    
    use \app\models\base\traits\MembershipTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organizationsMemberships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'orgId'], 'required'],
            [['userId', 'orgId', 'dateCreated', 'dateApproved'], 'default', 'value' => null],
            [['userId', 'orgId', 'dateCreated', 'dateApproved'], 'integer'],
            [['userId', 'orgId'], 'unique', 'targetAttribute' => ['userId', 'orgId']],
            [['orgId'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['orgId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    [
		'class' => TimestampBehavior::class,
		'createdAtAttribute' => 'dateCreated',
		'updatedAtAttribute' => false,
	    ],
	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'orgId' => 'Org ID',
            'dateCreated' => 'Date Created',
            'dateApproved' => 'Date Approved',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'orgId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['orgId', 'userId'];
    }
    /**
     * @param integer $userId
     * @return \yii\db\ActiveQuery
     */
    public static function findByUserId(int $userId)
    {
        return self::find()->andWhere(['userId' => $userId]);
    }
    
    /**
     * @param integer $orgId
     * @return \yii\db\ActiveQuery
     */
    public static function findByOrgId(int $orgId)
    {
        return self::find()->andWhere(['orgId' => $orgId]);
    }

}
