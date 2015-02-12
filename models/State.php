<?php

namespace app\models;

use Yii;
use app\components\MyModel;

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
            [['legislature', 'executive', 'state_structure', 'goverment_form', 'group_id','allow_register_parties'], 'integer'],
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

    public function getGovermentFields()
    {
        return $this->hasMany('app\models\GovermentFieldValue', array('state_id' => 'id'));
    }
}
