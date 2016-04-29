<?php

namespace app\models\massmedia;

use Yii;

/**
 * This is the model class for table "massmedia".
 *
 * @property integer $id
 * @property string $name
 * @property integer $protoId
 * @property integer $holdingId
 * @property integer $directorId
 * @property integer $stateId
 * @property integer $partyId
 * @property integer $popClassId
 * @property integer $regionId
 * @property integer $religionId
 * @property integer $ideologyId
 */
class Massmedia extends \app\components\MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'protoId', 'holdingId', 'directorId', 'stateId'], 'required'],
            [['name'], 'string'],
            [['protoId', 'holdingId', 'directorId', 'stateId', 'partyId', 'popClassId', 'regionId', 'religionId', 'ideologyId'], 'integer'],
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
            'protoId' => 'Proto ID',
            'holdingId' => 'Holding ID',
            'directorId' => 'Director ID',
            'stateId' => 'State ID',
            'partyId' => 'Party ID',
            'popClassId' => 'Pop Class ID',
            'regionId' => 'Region ID',
            'religionId' => 'Religion ID',
            'ideologyId' => 'Ideology ID',
        ];
    }
}
