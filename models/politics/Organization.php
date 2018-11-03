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
use app\models\government\State;

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
 * @property integer $type
 * @property integer $stateId
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 *
 * @property User $leader
 * @property Post $leaderPost
 * @property Membership[] $organizationMemberships
 * @property User[] $users
 * @property Post[] $posts
 * @property Ideology $ideology
 * @property State $state
 *
 * @property boolean $isDeleted
 * @property string $joiningRulesName
 * @property string $typeName
 */
class Organization extends ActiveRecord
{

    /**
     * Свободное вступление
     */
    const JOINING_RULES_OPEN = 1;

    /**
     * Вступление по заявкам
     */
    const JOINING_RULES_CLOSED = 2;

    /**
     * Вступление по приглашениям
     */
    const JOINING_RULES_PRIVATE = 3;

    /**
     * Организация без типа
     */
    const TYPE_DEFAULT = 0;

    /**
     * Незарегистрированная партия
     */
    const TYPE_UNREGISTERED_PARTY = 1;

    /**
     * Зарегистрированная партия
     */
    const TYPE_REGISTERED_PARTY = 2;

    /**
     * Частная компания
     */
    const TYPE_PRIVATE_COMPANY = 3;

    /**
     * Государственная компания
     */
    const TYPE_STATE_OWNED_COMPANY = 4;

    /**
     * Агенство (государственная организация)
     */
    const TYPE_AGENCY = 5;

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
	    [['name', 'nameShort', 'ideologyId'], 'required'],
	    [['leaderPostId', 'dateCreated', 'dateDeleted', 'stateId', 'utr'], 'default', 'value' => null],
	    [['type'], 'default', 'value' => 1],
	    [['leaderPostId', 'dateCreated', 'dateDeleted', 'utr', 'ideologyId', 'fame', 'trust', 'success', 'membersCount', 'joiningRules', 'type', 'stateId'], 'integer'],
	    [['name', 'flag', 'anthem'], 'string', 'max' => 255],
	    [['nameShort'], 'string', 'max' => 10],
	    [['text', 'textHtml'], 'string'],
	    [['utr'], 'unique'],
	    [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['leaderPostId' => 'id']],
	    [['ideologyId'], 'exist', 'skipOnError' => true, 'targetClass' => Ideology::class, 'targetAttribute' => ['ideologyId' => 'id']],
	    [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::class, 'targetAttribute' => ['stateId' => 'id']],
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
	    'name' => 'Название организации',
	    'nameShort' => 'Аббревиатура',
	    'flagFile' => 'Флаг',
	    'flag' => 'Флаг',
	    'anthem' => 'Гимн',
	    'ideologyId' => 'Идеология',
	    'joiningRules' => 'Правила вступления',
	    'utr' => 'ИНН',
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
    public function getState()
    {
	return $this->hasOne(State::class, ['id' => 'stateId']);
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
    public function getPosts()
    {
	return $this->hasMany(Post::class, ['orgId' => 'id']);
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
	    $this->addError('flagFile', 'Error #' . $this->flagFile->error);
	    return false;
	}

	$path = Yii::getAlias("@webroot/upload/organizations/{$this->id}/");
	if (!is_dir($path)) {
	    mkdir($path);
	}

//        Image::thumbnail($this->flagFile->tempName , 50, 50)->save($path.'50.jpg', ['quality' => 80]);
	Image::resize($this->flagFile->tempName, 300, null, true)->save($path . '300.jpg', ['quality' => 80]);

	$this->flag = '/upload/organizations/' . $this->id . '/300.jpg';
	return $this->save();
    }

    /**
     *
     * @param integer $id
     * @return Post
     */
    public function getPostByUserId(int $id)
    {
	$model = Post::findOne(['userId' => $id, 'orgId' => $this->id]);
    }

    /**
     *
     * @return array
     */
    public static function joiningRulesList()
    {
	return [
	    self::JOINING_RULES_OPEN => 'Свободное членство',
	    self::JOINING_RULES_CLOSED => 'Членство по заявкам',
	    self::JOINING_RULES_PRIVATE => 'Членство по приглашениям',
	];
    }

    /**
     *
     * @return array
     */
    public static function typesList()
    {
	return [
	    self::TYPE_DEFAULT => 'Некоммерческая организация',
	    self::TYPE_UNREGISTERED_PARTY => 'Политическая партия',
	    self::TYPE_REGISTERED_PARTY => 'Политическая партия',
	    self::TYPE_PRIVATE_COMPANY => 'Частная компания',
	    self::TYPE_STATE_OWNED_COMPANY => 'Государственная компания',
	    self::TYPE_AGENCY => 'Государственная организация',
	];
    }

    /**
     *
     * @return array
     */
    public static function typesListOnCreate()
    {
	return [
	    self::TYPE_DEFAULT => 'Некоммерческая организация',
	    self::TYPE_UNREGISTERED_PARTY => 'Политическая партия',
	    self::TYPE_PRIVATE_COMPANY => 'Частная компания',
	];
    }

    /**
     *
     * @return boolean
     */
    public function getIsDeleted(): bool
    {
	return !is_null($this->dateDeleted);
    }

    /**
     *
     * @return string
     */
    public function getJoiningRulesName(): string
    {
	return self::joiningRulesList()[$this->joiningRules];
    }

    /**
     *
     * @return string
     */
    public function getTypeName(): string
    {
	return self::typesList()[$this->type];
    }

}
