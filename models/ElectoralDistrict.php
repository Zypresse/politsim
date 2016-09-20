<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Вещь, передаваемая в сделке
 *
 * @property integer $id
 * @property integer $stateId
 * @property string $name
 * @property string $nameShort
 * 
 * @property State $state
 * @property Tile[] $tiles
 * 
 */
class ElectoralDistrict extends MyModel
{
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'electoral-districts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'name', 'nameShort'], 'required'],
            [['stateId'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }
        
    public function getState()
    {
        return $this->hasOne(State::classname(), ['id' => 'stateId']);
    }
    
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['id' => 'tileId'])
                ->viaTable('electoral-districts-to-tiles', ['districtId' => 'id']);
    }
}