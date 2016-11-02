<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "nations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $groupId
 * @property double $agressionBase
 * @property double $consciousnessBase
 */
class Nation extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'groupId'], 'required'],
            [['groupId'], 'integer', 'min' => 0],
            [['agressionBase', 'consciousnessBase'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'groupId' => Yii::t('app', 'Group ID'),
            'agressionBase' => Yii::t('app', 'Agression Base'),
            'consciousnessBase' => Yii::t('app', 'Consciousness Base'),
        ];
    }
}
