<?php

namespace app\models;

use app\components\MyModel;

/**
 * Пункт конституции. Таблица "goverment_field_values".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $state_id
 * @property string $value
 * 
 * @property GovermentFieldType $type
 * @property State $state
 */
class GovermentFieldValue extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goverment_field_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'state_id', 'value'], 'required'],
            [['type_id', 'state_id'], 'integer'],
            [['value'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'type_id'  => 'Type ID',
            'state_id' => 'State ID',
            'value'    => 'Value',
        ];
    }

    public function getType()
    {
        return $this->hasOne('app\models\GovermentFieldType', array('id' => 'type_id'));
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    
    /**
     * Не синхронизировать после сохранения
     * @var boolean false
     */
    public $noSync = false;

    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->noSync) {
            $this->type->syncronize($this);
        }

        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function syncronize()
    {
        $this->type->syncronize($this);
    }

}
