<?php

namespace app\models\economics;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Физический лежащий где-то кусок ресурса
 *
 * @property integer $id
 * @property integer $protoId прототип ресурса (напр. танк)
 * @property integer $subProtoId субпрототип ресурса (напр. Т-90)
 * @property integer $masterId ИНН владельца
 * @property integer $locationId ИНН местоположения
 * @property integer $quality качество ресурса
 * @property integer $deterioration износ ресурса
 * @property double $count количество единиц ресурса
 */
class Resource extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'masterId', 'locationId'], 'required'],
            [['protoId'], 'integer'],
            [['subProtoId', 'masterId', 'locationId', 'quality', 'deterioration'], 'integer', 'min' => 0],
            [['count'], 'number', 'min' => 0],
            [['protoId', 'subProtoId', 'masterId', 'locationId', 'quality', 'deterioration'], 'unique', 'targetAttribute' => ['protoId', 'subProtoId', 'masterId', 'locationId', 'quality', 'deterioration'], 'message' => 'The combination of Proto ID, Sub Proto ID, Master ID, Location ID, Quality and Deterioration has already been taken.'],
            [['locationId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['locationId' => 'id']],
            [['masterId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['masterId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'subProtoId' => Yii::t('app', 'Sub Proto ID'),
            'masterId' => Yii::t('app', 'Master ID'),
            'locationId' => Yii::t('app', 'Location ID'),
            'quality' => Yii::t('app', 'Quality'),
            'deterioration' => Yii::t('app', 'Deterioration'),
            'count' => Yii::t('app', 'Count'),
        ];
    }
    
    private $_proto = null;
    
    public function getProto()
    {
        if (is_null($this->_proto)) {
            $this->_proto = ResourceProto::getPrototype($this->protoId, $this->subProtoId);
        }
        return $this->_proto;
    }
    
}
