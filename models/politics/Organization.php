<?php

namespace app\models\politics;

use Yii;
use app\models\base\ActiveRecord;
use app\models\auth\User;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\imagine\Image;
use app\models\politics\OrganizationMembership as Membership;
use app\models\politics\OrganizationPost as Post;
use app\models\variables\Ideology;

/**
 * This is the model class for table "organizations".
 *
 * @property integer $id
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $leaderPostId
 * @property integer $ideologyId
 * @property integer $fame
 * @property integer $trust
 * @property integer $success
 * @property string $text
 * @property string $textHtml
 * @property integer $membersCount
 * @property integer $joiningRules
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 *
 * @property User $leader
 * @property Post $leaderPost
 * @property Membership[] $organizationMemberships
 * @property User[] $users
 * @property Ideology $ideology
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
            [['leaderId', 'dateCreated', 'dateDeleted', 'utr', 'leaderPostId', 'ideologyId', 'fame', 'trust', 'success', 'membersCount', 'joiningRules'], 'integer'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['text', 'textHtml'], 'string'],
            [['utr'], 'unique'],
            [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['leaderPostId' => 'id']],
            [['ideologyId'], 'exist', 'skipOnError' => true, 'targetClass' => Ideology::class, 'targetAttribute' => ['ideologyId' => 'id']],
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
        return $this->hasOne(User::class, ['id' => 'userId'])->via('leaderPost');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaderPost()
    {
        return $this->hasOne(Post::class, ['id' => 'leaderPostId']);
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getIdeology()
    {
        return $this->hasOne(Ideology::class, ['id' => 'ideologyId']);
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
