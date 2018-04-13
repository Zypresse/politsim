<?php

namespace app\models\politics;

use Yii;
use app\models\base\ActiveRecord;
use app\models\auth\User;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\imagine\Image;

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
     *
     * @var \yii\web\UploadedFile
     */
    public $flagFile;
    
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
            [['leaderId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['leaderId' => 'id']],
            [['flagFile'], 'file', 'maxFiles' => 1],
            [['flagFile'], 'safe'],
            [['anthem'], 'validateAnthem'],
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
    
    /**
     * 
     * @return boolean
     */
    public function saveFlag()
    {
        $this->flagFile = UploadedFile::getInstance($this, 'flagFile');
        if ($this->flagFile->error) {
            $this->addError('flagFile', 'Error #'.$this->flagFile->error);
            return false;
        }
        
        $path = Yii::getAlias("@webroot/upload/organizations/{$this->id}/");
        if (!is_dir($path)) {
            mkdir($path);
        }
        
//        Image::thumbnail($this->flagFile->tempName , 50, 50)->save($path.'50.jpg', ['quality' => 80]);
        Image::resize($this->flagFile->tempName, 300, null, true)->save($path.'300.jpg', ['quality' => 80]);
        
        $this->flag = '/upload/organizations/'.$this->id.'/300.jpg';
        return $this->save();
    }
}
