<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Конституция региона
 * 
 * @property integer $regionId
 * @property integer $leaderPostId
 * 
 * @property Region $region
 * @property AgencyPost $leaderPost
 * 
 */
class RegionConstitution extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regionId'], 'required'],
            [['regionId', 'leaderPostId'], 'integer', 'min' => 0],
            [['regionId'], 'unique'],
            [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['leaderPostId' => 'id']],
            [['regionId'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'leaderPostId' => Yii::t('app', 'Leader post')
        ];
    }
    
    public static function generate()
    {
        return new static();
    }
    
    public static function primaryKey()
    {
        return ['regionId'];
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }
    
    public function getLeaderPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'leaderPostId']);
    }
}
