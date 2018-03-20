<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;

/**
 * This is the model class for table "polygons".
 *
 * @property integer $id
 * @property integer $ownerType
 * @property integer $ownerId
 * @property array $data
 */
class Polygon extends ActiveRecord
{

    const TYPE_CITY = 1;
    const TYPE_REGION = 2;
    const TYPE_STATE = 3;
    const TYPE_ELECTORAL_DISTRICT = 4;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'polygons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ownerType', 'ownerId', 'data'], 'required'],
            [['ownerType', 'ownerId'], 'default', 'value' => null],
            [['ownerType', 'ownerId'], 'integer'],
            [['data'], 'safe'],
            [['ownerType', 'ownerId'], 'unique', 'targetAttribute' => ['ownerType', 'ownerId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ownerType' => 'Owner Type',
            'ownerId' => 'Owner ID',
            'data' => 'Data',
        ];
    }

}
