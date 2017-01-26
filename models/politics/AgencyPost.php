<?php

namespace app\models\politics;

use Yii,
    app\models\economics\UtrType,
    app\models\politics\constitution\Constitution,
    app\models\politics\constitution\ConstitutionArticle,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\models\politics\elections\Election,
    app\models\politics\elections\ElectionManager,
    app\models\politics\elections\ElectionOwner,
    app\models\politics\elections\ElectionWhomType,
    app\models\User;

/**
 * Государственные посты 
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $partyId
 * @property integer $userId
 * @property string $name
 * @property string $nameShort
 * @property integer $utr
 * 
 * @property Agency[] $agencies
 * @property State $state
 * @property Party $party
 * @property User $user
 * @property Election[] $elections
 * @property Constitution $constitution
 * @property ConstitutionArticle[] $articles
 * 
 * @property AgencyPost[] $postsDestignated
 * 
 */
class AgencyPost extends ElectionOwner
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agenciesPosts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'partyId', 'userId'], 'integer', 'min' => 0],
            [['stateId', 'name', 'nameShort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
            [['partyId'], 'exist', 'skipOnError' => true, 'targetClass' => Party::className(), 'targetAttribute' => ['partyId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Short name'),
        ];
    }
    
    public function getAgencies()
    {
        return $this->hasMany(Agency::className(), ['id' => 'agencyId'])
                ->viaTable('agenciesToPosts', ['postId' => 'id']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getParty()
    {
        return $this->hasOne(Party::className(), ['id' => 'partyId']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
            
    public function getElections()
    {
        return $this->hasMany(Election::className(), ['whomId' => 'id'])->where(['whomType' => ElectionWhomType::POST]);
    }
    
    private $_postsDestignated = null;
    public function getPostsDestignated()
    {
        if (is_null($this->_postsDestignated)) {
            $articles = ConstitutionArticle::find()
                    ->where(['ownerType' => ConstitutionOwnerType::POST])
                    ->andWhere(['value' => DestignationType::BY_OTHER_POST, 'value2' => $this->id])
                    ->with('owner')
                    ->with('owner.user')
                    ->all();
            $this->_postsDestignated = [];
            foreach ($articles as $article) {
                $this->_postsDestignated[] = $article->owner;
            }
        }
        return $this->_postsDestignated;
    }
    
    /**
     * 
     * @return Election
     */
    public function getNextElection()
    {
        $election = $this->getElections()->where(['results' => null])->one();
        if (is_null($election)) {
            $election = ElectionManager::createPostElection($this);
        }
        return $election;
    }
    
    
    public function isElected() : bool
    {
        $article = $this->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        if (!is_null($article)) {
            switch ((int) $article->value) {
                case DestignationType::BY_STATE_ELECTION:
                case DestignationType::BY_AGENCY_ELECTION:
                case DestignationType::BY_DISTRICT_ELECTION:
                    return true;
            }
        }
        return false;
    }

    public function getTaxStateId(): int
    {
        return $this->stateId;
    }

    public function getUserControllerId()
    {
        return $this->userId;
    }

    public function getUtrType(): int
    {
        return UtrType::POST;
    }

    public function isGoverment($stateId): bool
    {
        return $this->stateId == $stateId;
    }

    public function isTaxedInState($stateId): bool
    {
        return $this->stateId == $stateId;
    }

    public function isUserController($userId): bool
    {
        return $this->userId == $userId;
    }

    public static function getConstitutionOwnerType(): int
    {
        return ConstitutionOwnerType::POST;
    }
    
    public function removeUser()
    {
        // TODO: notification
        $this->userId = null;
        return $this->save();
    }
    
    public function destignate(User $user)
    {
        if ($this->user) {
            $this->removeUser();
        }
        // TODO: notification
        $this->userId = $user->id;
        return $this->save();
    }
    
    public function canBeDeleted()
    {
        return !ConstitutionArticle::find()
                ->where(['type' => ConstitutionArticleType::LEADER_POST, 'value' => $this->id])
                ->exists() &&
                !ConstitutionArticle::find()
                ->where(['type' => ConstitutionArticleType::DESTIGNATION_TYPE, 'value' => DestignationType::BY_OTHER_POST, 'value2' => $this->id])
                ->exists();
    }
    
    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('agenciesToPosts', ['postId' => $this->id])->execute();
        ConstitutionArticle::deleteAll(['ownerType' => $this->getConstitutionOwnerType(), 'ownerId' => $this->id]);
        Election::deleteAll(['whomType' => ElectionWhomType::POST, 'whomId' => $this->id]);
        return parent::afterDelete();
    }

}
