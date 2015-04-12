<?php

namespace app\models;

use app\components\MyModel;

/**
 * This is the model class for table "elect_orgleader_requests".
 *
 * @property integer $id
 * @property integer $org_id Организация, лидер которой избирается
 * @property integer $uid 
 * @property integer $party_id
 * 
 * @property Org $org Организация, лидер которой избирается
 * @property User $candidat
 * @property Party $party
 */
class ElectOrgLeaderRequest extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elect_orgleader_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'uid', 'party_id'], 'required'],
            [['org_id', 'uid', 'party_id'], 'integer'],
            [['org_id', 'party_id'], 'unique', 'targetAttribute' => ['org_id', 'party_id'], 'message' => 'Заявка от партии уже подана.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Организация, лидер которой избирается',
            'uid' => 'Кандидат',
            'party_id' => 'Партия',
        ];
    }
    
    public function getOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'org_id'));
    }
    
    public function getCandidat()
    {
        return $this->hasOne('app\models\User', array('id' => 'uid'));
    }
    
    public function getParty()
    {
        return $this->hasOne('app\models\Party', array('id' => 'party_id'));
    }
}