<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\components\TaxPayer,
    app\components\MyHtmlHelper,
    app\models\massmedia\proto\MassmediaProto,
    app\models\massmedia\MassmediaEditor,
    app\models\massmedia\MassmediaPost,
    app\models\Holding,
    app\models\User,
    app\models\State,
    app\models\Party,
    app\models\PopClass,
    app\models\PopNation,
    app\models\Population,
    app\models\Region,
    app\models\Religion,
    app\models\Ideology;

/**
 * This is the model class for table "massmedia".
 *
 * @property integer $id
 * @property string $name
 * @property integer $protoId
 * @property integer $holdingId
 * @property integer $directorId
 * @property integer $stateId
 * @property integer $partyId
 * @property integer $popClassId
 * @property integer $popNationId
 * @property integer $regionId
 * @property integer $religionId
 * @property integer $ideologyId
 * @property integer $coverage
 * @property integer $rating
 * @property integer $utr
 * @property double $balance
 * @property integer $created
 * 
 * @property MassmediaProto $proto
 * @property Holding $holding
 * @property User $director
 * @property State $state
 * @property Party $party
 * @property PopClass $popClass
 * @property PopNation $popNation
 * @property Region $region
 * @property Religion $religion
 * @property Ideology $ideology
 * @property MassmediaEditor[] $editors
 * @property MassmediaEditor[] $publicEditors
 * @property MassmediaPost[] $posts
 * 
 * @property Population[] $audience
 * 
 */
class Massmedia extends MyModel //implements TaxPayer
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'protoId', 'holdingId', 'directorId', 'stateId'], 'required'],
            [['name'], 'string'],
            [['protoId', 'holdingId', 'directorId', 'stateId', 'partyId', 'popClassId', 'popNationId', 'regionId', 'religionId', 'ideologyId', 'rating', 'coverage', 'utr', 'created'], 'integer'],
            [['balance'], 'number'],
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
            'protoId' => 'Proto ID',
            'holdingId' => 'Holding ID',
            'directorId' => 'Director ID',
            'stateId' => 'State ID',
            'partyId' => 'Party ID',
            'popClassId' => 'Pop Class ID',
            'popNationId' => 'Pop Nation ID',
            'regionId' => 'Region ID',
            'religionId' => 'Religion ID',
            'ideologyId' => 'Ideology ID',
            'rating' => 'Rating',
            'coverage' => 'Coverage',
            'utr' => 'UTR',
            'balance' => 'Balance',
            'created' => 'Created',
        ];
    }
    
    public function getEditors()
    {
        return $this->hasMany(MassmediaEditor::className(), array('massmediaId' => 'id'));
    }
    
    public function getPublicEditors()
    {
        return $this->hasMany(MassmediaEditor::className(), array('massmediaId' => 'id'))->where(['hide' => 0]);
    }
    
    public function getPosts()
    {
        return $this->hasMany(MassmediaPost::className(), array('massmediaId' => 'id'));
    }
    
    public function getDirector()
    {
        return $this->hasOne(User::className(), array('id' => 'directorId'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'stateId'));
    }
    
    public function getParty()
    {
        return $this->hasOne(Party::className(), array('id' => 'partyId'));
    }
    
    public function getPopClass()
    {
        return $this->hasOne(PopClass::className(), array('id' => 'popClassId'));
    }
    
    public function getPopNation()
    {
        return $this->hasOne(PopNation::className(), array('id' => 'popNationId'));
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'regionId'));
    }
    
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holdingId'));
    }
    
    public function getIdeology()
    {
        return $this->hasOne(Ideology::className(), array('id' => 'ideologyId'));
    }
    
    public function getReligion()
    {
        return $this->hasOne(Religion::className(), array('id' => 'religionId'));
    }
    
    public function getProto()
    {
        return MassmediaProto::instantiateById($this->protoId);
    }
    
    public static function findNewspapers()
    {
        return static::find()->where(['protoId' => 1]);
    }
    
    /**
     * 
     * @return yii\db\ActiveQuery
     */
    public function getAudienceQuery()
    {
        $query = Population::find();
        
        if ($this->popClassId) {
            $query = $query->andWhere([
                'class' => $this->popClassId
            ]);
        }
        
        if ($this->popNationId) {
            $query = $query->andWhere([
                'nation' => $this->popNationId
            ]);
        }
        
        if ($this->ideologyId) {
            $query = $query->andWhere([
                'ideology' => $this->ideologyId
            ]);
        }
        
        if ($this->religionId) {
            $query = $query->andWhere([
                'religion' => $this->religionId
            ]);
        }
        
        if ($this->regionId) {
            $query = $query->andWhere([
                'region_id' => $this->regionId
            ]);
        } else {
            $query = $query->andWhere('region_id IN (SELECT id FROM regions WHERE state_id = :sid)',[
                ':sid' => $this->stateId
            ]);
        }
        
        return $query;
    }
    
    public function getAudience()
    {
        return $this->getAudienceQuery()->all();
    }
    
    public function calcCoverage($save = false)
    {        
        $this->coverage = intval($this->getAudienceQuery()->sum('count'));
        
        if ($save) {
            return $this->save();
        }
    }
    
    /**
     * 
     * @param integer $userId
     * @return boolean
     */
    public function isEditor($userId)
    {
        return $this->directorId === $userId || (MassmediaEditor::find()
                ->where(['userId' => $userId, 'massmediaId' => $this->id])
                ->count() > 0);
    }
    
    /**
     * 
     * @param integer $userId
     * @return MassmediaEditor
     */
    public function getEditorRules($userId)
    {
        return MassmediaEditor::find()
            ->where(['userId' => $userId, 'massmediaId' => $this->id])
            ->one();
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a($this->name, 'load_page("newspaper",{id:'.$this->id.'})');
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $editor = MassmediaEditor::generateRoot();
            $editor->load([
                'userId' => $this->directorId,
                'massmediaId' => $this->id
            ],'');
            
            $editor->save();
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
}