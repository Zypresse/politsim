<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Государственные посты 
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $partyId
 * @property integer $userId
 * @property string $name
 * @property string $nameShort
 * 
 * @property Agency[] $agencies
 * @property State $state
 * @property Party $party
 * @property User $user
 * @property AgencyPostConstitution $constitution
 * 
 */
class AgencyPost extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agencies-posts';
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
                ->viaTable('agencies-to-posts', ['postId' => 'id']);
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
    
    public function getConstitution()
    {
        return $this->hasOne(AgencyPostConstitution::className(), ['postId' => 'id']);
    }
    
}
