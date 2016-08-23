<?php

namespace app\models\events;

use Yii,
    app\components\MyModel,
    app\models\events\proto\EventProto;

/**
 * События игрового мира. Таблица "events".
 *
 * @property integer $id
 * @property integer $protoId
 * @property string $data
 * @property integer $created
 * @property integer $ended
 */
class Event extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId'], 'required'],
            [['protoId', 'created', 'ended'], 'integer'],
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
            'data' => 'Data',
            'created' => 'Created',
            'ended' => 'Ended',
        ];
    }

    public function getProto()
    {
        return EventProto::instantiateById($this->protoId);
    }
}
