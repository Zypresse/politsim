<?php

namespace app\models\politics;

use Yii,
    app\models\economics\UtrType,
    app\models\politics\constitution\Constitution,
    app\models\politics\constitution\ConstitutionArticle,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\models\politics\elections\Election,
    app\models\politics\elections\ElectionManager,
    app\models\politics\elections\ElectionOwner,
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
        return $this->hasMany(Election::className(), ['whomId' => 'id'])->where(['whomType' => elections\ElectionWhomType::POST]);
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

}
