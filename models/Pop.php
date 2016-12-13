<?php

namespace app\models;

use Yii;
use app\components\TaxPayerModel;

/**
 * This is the model class for table "pops".
 *
 * @property integer $count
 * @property integer $classId
 * @property integer $nationId
 * @property integer $tileId
 * @property string $ideologies
 * @property string $religions
 * @property string $genders
 * @property string $ages
 * @property double $contentment
 * @property double $agression
 * @property double $consciousness
 * @property integer $dateLastWageGet
 * @property integer $utr
 * 
 * @property Tile $tile
 * @property Nation $nation
 * 
 */
class Pop extends TaxPayerModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classId', 'nationId', 'tileId', 'ideologies', 'religions', 'genders', 'ages'], 'required'],
            [['count', 'classId', 'nationId', 'tileId', 'dateLastWageGet', 'utr'], 'integer', 'min' => 0],
            [['contentment', 'agression', 'consciousness'], 'number', 'min' => 0],
            [['ideologies', 'religions', 'genders', 'ages'], 'string'],
            [['tileId', 'classId', 'nationId'], 'unique', 'targetAttribute' => ['tileId', 'classId', 'nationId'], 'message' => 'The combination of Class ID, Nation ID and Tile ID has already been taken.'],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['tileId'], 'exist', 'skipOnError' => true, 'targetClass' => Tile::className(), 'targetAttribute' => ['tileId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'count' => Yii::t('app', 'Count'),
            'classId' => Yii::t('app', 'Class ID'),
            'nationId' => Yii::t('app', 'Nation ID'),
            'tileId' => Yii::t('app', 'Tile ID'),
            'ideologies' => Yii::t('app', 'Ideologies'),
            'religions' => Yii::t('app', 'Religions'),
            'genders' => Yii::t('app', 'Genders'),
            'ages' => Yii::t('app', 'Ages'),
            'contentment' => Yii::t('app', 'Contentment'),
            'agression' => Yii::t('app', 'Agression'),
            'consciousness' => Yii::t('app', 'Consciousness'),
            'dateLastWageGet' => Yii::t('app', 'Date Last Wage Get'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getTile()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tileId']);
    }
    
    public function getNation()
    {
        return Nation::findOne($this->nationId);
    }
    
    public function getTaxStateId()
    {
        return $this->tile->region ? $this->tile->region->stateId : null;
    }

    public function isTaxedInState($stateId)
    {
        return $this->tile->region ? $this->tile->region->stateId == $stateId : false;
    }

    public function getUserControllerId()
    {
        return null;
    }

    public function isUserController($userId)
    {
        return false;
    }

    public function getUtrType()
    {
        return Utr::TYPE_POP;
    }

    public function isGoverment($stateId)
    {
        return false;
    }
    
    public static function primaryKey() {
        return ['classId', 'nationId', 'tileId'];
    }

}
