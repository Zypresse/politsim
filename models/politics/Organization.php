<?php

namespace app\models\politics;

use Yii;
use app\models\base\ActiveRecord;
use app\models\auth\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "organizations".
 *
 * @property integer $id
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $leaderId
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 *
 * @property User $leader
 * @property OrganizationMembership[] $organizationMemberships
 * @property User[] $users
 */
class Organization extends ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameShort'], 'required'],
            [['leaderId', 'dateCreated', 'dateDeleted', 'utr'], 'default', 'value' => null],
            [['leaderId', 'dateCreated', 'dateDeleted', 'utr'], 'integer'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['utr'], 'unique'],
            [['leaderId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['leaderId' => 'id']],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'nameShort' => 'Name Short',
            'flag' => 'Flag',
            'anthem' => 'Anthem',
            'leaderId' => 'Leader ID',
            'dateCreated' => 'Date Created',
            'dateDeleted' => 'Date Deleted',
            'utr' => 'Utr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(User::class, ['id' => 'leaderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationMemberships()
    {
        return $this->hasMany(OrganizationMembership::class, ['orgId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'userId'])->viaTable('organizationsMemberships', ['orgId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findActive()
    {
        return self::find()->andWhere(['dateDeleted' => null]);
    }
    
}
