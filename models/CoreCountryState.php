<?php

namespace app\models;

use app\components\MyModel,
    app\models\CoreCountry,
    app\models\State;

/**
 * Привязка государств к коренным странам
 *
 * @property integer $id
 * @property integer $core_id
 * @property integer $state_id
 * @property double $percents
 * 
 * @property CoreCountry $core
 * @property State $state
 */
class CoreCountryState extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cores_countries_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['core_id', 'state_id', 'percents'], 'required'],
            [['core_id', 'state_id'], 'integer'],
            [['percents'], 'number'],
            [['core_id', 'state_id'], 'unique', 'targetAttribute' => ['core_id', 'state_id'], 'message' => 'The combination of Core ID and State ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'core_id' => 'Core ID',
            'state_id' => 'State ID',
            'percents' => 'Percents',
        ];
    }
    
    
    public function getCore()
    {
        return $this->hasOne(CoreCountry::className(), array('id' => 'core_id'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
}