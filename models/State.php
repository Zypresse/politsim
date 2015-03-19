<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\GovermentFieldType;
use app\models\GovermentFieldValue;

/**
 * This is the model class for table "states".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $capital
 * @property string $color
 * @property integer $legislature
 * @property integer $executive
 * @property integer $state_structure
 * @property integer $goverment_form
 * @property integer $group_id
 * @property integer $population
 * @property integer $sum_star
 * @property integer $allow_register_parties
 * @property integer $leader_can_drop_legislature
 * @property integer $allow_register_holdings
 * @property integer $register_parties_cost
 */
class State extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'capital'], 'required'],
            [['legislature', 'executive', 'state_structure', 'goverment_form', 'group_id','allow_register_parties','population','sum_star','leader_can_drop_legislature','allow_register_holdings','register_parties_cost'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 10],
            [['capital', 'color'], 'string', 'max' => 7]
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
            'short_name' => 'Short Name',
            'capital' => 'Capital',
            'color' => 'Color',
            'legislature' => 'Legislature',
            'executive' => 'Executive',
            'state_structure' => 'State Structure',
            'goverment_form' => 'Goverment Form',
            'group_id' => 'Group ID',
        ];
    }
    
    public function getLegislatureOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'legislature'));
    }
    
    public function getExecutiveOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'executive'));
    }

    public function getStructure()
    {
        return $this->hasOne('app\models\Structure', array('id' => 'state_structure'));
    }

    public function getGovermentForm()
    {
        return $this->hasOne('app\models\GovermentForm', array('id' => 'goverment_form'));
    }

    public function getCapitalRegion()
    {
        return $this->hasOne('app\models\Region', array('code' => 'capital'));
    }

    public function getRegions()
    {
        return $this->hasMany('app\models\Region', array('state_id' => 'id'))->orderBy('name');
    }
    public function getCities()
    {
        return $this->hasMany('app\models\Region', array('state_id' => 'id'))->orderBy('city');
    }
    public function getLicenses()
    {
        return $this->hasMany('app\models\StateLicense', array('state_id' => 'id'));
    }

    public function getGovermentFields()
    {
        return $this->hasMany('app\models\GovermentFieldValue', array('state_id' => 'id'));
    }
    public function getParties()
    {
        return $this->hasMany('app\models\Party', array('state_id' => 'id'));
    }
    public function getUsers()
    {
        return $this->hasMany('app\models\User', array('state_id' => 'id'));
    }
    
    public function afterDelete()
    {
        if ($this->legislatureOrg) $this->legislatureOrg->delete();
        if ($this->executiveOrg) $this->executiveOrg->delete();
        
        foreach ($this->regions as $region) {
            $region->state_id = 0;
        }
        foreach ($this->govermentFields as $gf) {
            $gf->delete();
        }
        foreach ($this->parties as $party) {
            $party->delete();
        }
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $gftAr = GovermentFieldType::find()->all();
            foreach ($gftAr as $gft) {
                $gfv = new GovermentFieldValue();
                $gfv->state_id = $this->id;
                $gfv->type_id = $gft->id;
                $gfv->value = $gft->default_value;
                $gfv->save();
            }
        }
        
        return parent::afterSave($insert,$changedAttributes);
    }
}
