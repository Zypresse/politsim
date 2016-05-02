<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\proto\MassmediaProto,
    app\models\Holding,
    app\models\User,
    app\models\State,
    app\models\Party,
    app\models\PopClass,
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
 * @property integer $regionId
 * @property integer $religionId
 * @property integer $ideologyId
 * @property integer $rating
 * 
 * @property MassmediaProto $proto
 * @property Holding $holding
 * @property User $director
 * @property State $state
 * @property Party $party
 * @property PopClass $popClass
 * @property Region $region
 * @property Religion $religion
 * @property Ideology $ideology
 */
class Massmedia extends MyModel
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
            [['protoId', 'holdingId', 'directorId', 'stateId', 'partyId', 'popClassId', 'regionId', 'religionId', 'ideologyId', 'rating'], 'integer'],
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
            'regionId' => 'Region ID',
            'religionId' => 'Religion ID',
            'ideologyId' => 'Ideology ID',
            'rating' => 'Rating',
        ];
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
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'RegionId'));
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
}
