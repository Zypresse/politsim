<?php

namespace app\models\politics;

use Yii;
use app\models\base\ActiveRecord;
use app\models\auth\User;

/**
 * This is the model class for table "organizationsPosts".
 *
 * @property integer $id
 * @property integer $orgId
 * @property integer $userId
 * @property string $name
 * @property string $nameShort
 * @property integer $powers
 * @property integer $appointmentType
 * @property integer $successorId
 *
 * @property Organization $organization
 * @property User $user
 * @property User $successor
 */
class OrganizationPost extends ActiveRecord
{

    /**
     * Дефолтные данные для создания поста лидера новой организации
     */
    const DEFAULT_LEADER_DATA = [
        'name' => 'Лидер',
        'nameShort' => 'Л',
        'powers' => 0,
        'appointmentType' => 0,
        'successorId' => null,
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organizationsPosts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orgId', 'name', 'nameShort', 'powers', 'appointmentType'], 'required'],
            [['orgId', 'userId', 'powers', 'appointmentType', 'successorId'], 'default', 'value' => null],
            [['orgId', 'userId', 'powers', 'appointmentType', 'successorId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['orgId'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['orgId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
            [['successorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['successorId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orgId' => 'Org ID',
            'userId' => 'User ID',
            'name' => 'Name',
            'nameShort' => 'Name Short',
            'powers' => 'Powers',
            'appointmentType' => 'Appointment Type',
            'successorId' => 'Successor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::class, ['id' => 'orgId']);
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
    public function getSuccessor()
    {
        return $this->hasOne(User::class, ['id' => 'successorId']);
    }

}
