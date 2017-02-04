<?php

namespace app\models\population;

use Yii,
    app\models\Tile,
    app\models\politics\City,
    app\models\politics\Region,
    app\models\economics\UtrType,
    app\models\economics\TaxPayerModel;

/**
 * This is the model class for table "pops".
 *
 * @property integer $id
 * @property integer $count
 * @property integer $classId
 * @property integer $nationId
 * @property integer $tileId
 * @property string $ideologies
 * @property string $religions
 * @property string $genders
 * @property string $ages
 * @property double $contentmentLow
 * @property double $contentmentMiddle
 * @property double $contentmentHigh
 * @property double $agression
 * @property double $consciousness
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
            [['count', 'classId', 'nationId', 'tileId', 'utr'], 'integer', 'min' => 0],
            [['contentmentLow', 'contentmentMiddle', 'contentmentHigh', 'agression', 'consciousness'], 'number', 'min' => 0],
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
            'contentmentLow' => Yii::t('app', 'Contentment Low'),
            'contentmentMiddle' => Yii::t('app', 'Contentment Middle'),
            'contentmentHigh' => Yii::t('app', 'Contentment High'),
            'agression' => Yii::t('app', 'Agression'),
            'consciousness' => Yii::t('app', 'Consciousness'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getTile()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tileId']);
    }
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId'])
                ->via('tile');
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId'])
                ->via('tile');
    }
    
    public function getNation()
    {
        return Nation::findOne($this->nationId);
    }
    
    public function getTaxStateId()
    {
        return $this->tile->region ? (int)$this->tile->region->stateId : null;
    }

    public function isTaxedInState(int $stateId)
    {
        return $this->tile->region ? (int)$this->tile->region->stateId == $stateId : false;
    }

    public function getUserControllerId()
    {
        return null;
    }

    public function isUserController(int $userId)
    {
        return false;
    }

    public function getUtrType()
    {
        return UtrType::POP;
    }

    public function isGoverment(int $stateId)
    {
        return false;
    }
        
    /**
     * 
     * @return array
     */
    public function getPseudoGroups()
    {
        $tmpPopsWithIdeologies = [];
        $ideologies = json_decode($this->ideologies, true);
        foreach ($ideologies as $ideologyId => $percents) {
            $tmpPopsWithIdeologies[] = [
                'ideologyId' => $ideologyId,
                'count' => $this->count*$percents/100
            ];
        }
        $tmpPopsWithReligions = [];
        $religions = json_decode($this->religions, true);
        foreach ($religions as $religionId => $percents) {
            foreach ($tmpPopsWithIdeologies as $tmpPop) {
                $tmpPopsWithReligions[] = [
                    'ideologyId' => $tmpPop['ideologyId'],
                    'religionId' => $religionId,
                    'count' => $tmpPop['count']*$percents/100
                ];
            }
        }
        unset($tmpPopsWithIdeologies);
        $tmpPopsWithGenders = [];
        $genders = json_decode($this->genders, true);
        foreach ($genders as $gender => $percents) {
            foreach ($tmpPopsWithReligions as $tmpPop) {
                $tmpPopsWithGenders[] = [
                    'ideologyId' => $tmpPop['ideologyId'],
                    'religionId' => $tmpPop['religionId'],
                    'gender' => $gender,
                    'count' => $tmpPop['count']*$percents/100
                ];
            }
        }
        unset($tmpPopsWithReligions);
        $tmpPops = [];
        $ages = json_decode($this->ages, true);
        foreach ($ages as $age => $percents) {
            foreach ($tmpPopsWithGenders as $tmpPop) {
                $tmpPops[] = [
                    'ideologyId' => $tmpPop['ideologyId'],
                    'religionId' => $tmpPop['religionId'],
                    'gender' => $tmpPop['gender'],
                    'age' => $age,
                    'nationId' => $this->nationId,
                    'classId' => $this->classId,
                    'tileId' => $this->tileId,
                    'count' => round($tmpPop['count']*$percents/100)
                ];
            }
        }
        
        return $tmpPops;
    }

}
