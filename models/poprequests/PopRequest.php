<?php

namespace app\models\poprequests;

use Yii,
    app\components\MyModel,
    app\models\poprequests\proto\PopRequestProto,
    app\models\events\Event;

/**
 * Запросы населения. Таблица "pop_requests".
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $eventId
 * @property string $data
 */
class PopRequest extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pop_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId'], 'required'],
            [['protoId', 'eventId'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'protoId' => 'Proto ID',
            'eventId' => 'Event ID',
            'data' => 'Data',
        ];
    }
    
    public function getEvent()
    {
        return $this->hasOne(Event::className(), array('id' => 'eventId'));
    }
    
    public function getProto()
    {
        return PopRequestProto::instantiateById($this->protoId);
    }
}
