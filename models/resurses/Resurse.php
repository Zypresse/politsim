<?php

namespace app\models\resurses;

use app\models\objects\MovableObject,
    app\models\resurses\ResurseCost,
    app\models\resurses\proto\ResurseProto;

/**
 * This is the model class for table "resurses".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $place_id
 * @property double $count
 *
 * @property proto\ResurseProto $proto
 * @property ResurseCost[] $costs
 */
class Resurse extends MovableObject
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id', 'proto_id', 'count'], 'required'],
            [['place_id', 'proto_id'], 'integer'],
            [['count'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => 'Place ID',
            'proto_id' => 'Proto ID',
            'count' => 'Count',
        ];
    }
    
    public function getProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'proto_id'));
    }
    
    public function getCosts()
    {
        return $this->hasMany(ResurseCost::className(), array('resurse_id' => 'id'));
    }
    
    public function getLocatedStateId() {
        return $this->place->getLocatedStateId();
    }

}