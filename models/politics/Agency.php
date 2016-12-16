<?php

namespace app\models\politics;

use Yii,
    app\models\politics\elections\Election,
    app\models\politics\constitution\AgencyConstitution,
    app\models\politics\constitution\AgencyPostConstitution,
    app\models\economics\TaxPayerModel,
    app\models\economics\Utr,
    app\models\economics\UtrType;

/**
 * Гос. организация
 *
 * @property integer $id
 * @property integer $stateId
 * @property string $name
 * @property string $nameShort
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property State $state
 * @property AgencyConstitution $constitution
 * @property AgencyPost[] $posts
 * @property Election[] $elections
 * 
 */
class Agency extends TaxPayerModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'name', 'nameShort'], 'required'],
            [['stateId', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getUtrType()
    {
        return UtrType::AGENCY;
    }

    public function getTaxStateId()
    {
        return $this->stateId;
    }

    public function isTaxedInState($stateId)
    {
        return $this->stateId == $stateId;
    }

    public function isGoverment($stateId)
    {
        return $this->stateId == $stateId;
    }
    
    public function getUserControllerId()
    {
        return false;
    }

    public function isUserController($userId)
    {
        return false;
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getConstitution()
    {
        return $this->hasOne(AgencyConstitution::className(), ['agencyId' => 'id']);
    }
    
    public function getPosts()
    {
        return $this->hasMany(AgencyPost::className(), ['id' => 'postId'])
                ->viaTable('agencies-to-posts', ['agencyId' => 'id']);
    }
    
    public function getElections()
    {
        return $this->hasMany(Election::className(), ['agencyId' => 'id']);
    }
    
    public function getNextElection()
    {
        if (!in_array($this->constitution->assignmentRule, [AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PLURARITY, AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PROPORTIONAL])) {
            return null;
        }
        $election = $this->getElections()->orderBy(['id' => SORT_DESC])->where(['results' => null])->one();
        if (is_null($election)) {
            $election = new Election([
                'protoId' => $this->constitution->assignmentRule,
                'agencyId' => $this->id,
                'isOnlyParty' => true,
                'dateRegistrationStart' => time(),
                'dateVotingStart' => time() + 24*60*60*$this->constitution->termOfElectionsRegistration,
                'dateVotingEnd' => time() + 24*60*60*$this->constitution->termOfElectionsRegistration + 24*60*60*$this->constitution->termOfElections
            ]);
            if (!$election->save()) {
                throw new \yii\base\Exception("Can not save elections object!");
            }
        }
        return $election;
    }

    public function beforeSave($insert)
    {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function updateTempPosts()
    {
        $count = $this->constitution ? $this->constitution->tempPostsCount : 0;
        $leaderPostId = $this->constitution ? $this->constitution->leaderPostId : 0;
        $tempPostId = $this->constitution ? $this->constitution->tempPostId : 0;
        if ($count > 0 && $tempPostId) {
            $tempPost = null;
            foreach ($this->posts as $post) {
                if ($post->id == $leaderPostId) {
                    continue;
                }
                if ($post->id == $tempPostId) {
                    $tempPost = $post;
                    continue;
                }
                $post->delete();
            }
            if ($tempPost && $tempPost->constitution) {
                for ($i = 0; $i < $count-1; $i++) {
                    $post = new AgencyPost([
                        'stateId' => $tempPost->stateId,
                        'name' => $tempPost->name,
                        'nameShort' => $tempPost->nameShort
                    ]);
                    $post->save();
                    $postConstitution = AgencyPostConstitution::generate();
                    $postConstitution->postId = $post->id;
                    $postConstitution->assignmentRule = $tempPost->constitution->assignmentRule;
                    $postConstitution->electionsRules = $tempPost->constitution->electionsRules;
                    $postConstitution->powers = $tempPost->constitution->powers;
                    $postConstitution->termOfElections = $tempPost->constitution->termOfElections;
                    $postConstitution->termOfElectionsRegistration = $tempPost->constitution->termOfElectionsRegistration;
                    $postConstitution->termOfOffice = $tempPost->constitution->termOfOffice;
                    $postConstitution->save();
                    $post->link('agencies', $this);
                }
            }
        }
    }

}
