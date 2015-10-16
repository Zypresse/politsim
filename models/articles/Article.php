<?php

namespace app\models\articles;

use app\components\MyModel;

/**
 * Пункт конституции. Таблица "articles".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $state_id
 * @property string $value
 * 
 * @property proto\ArticleProto $proto
 * @property State $state
 */
class Article extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proto_id', 'state_id', 'value'], 'required'],
            [['proto_id', 'state_id'], 'integer'],
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
            'proto_id'  => 'Type ID',
            'state_id' => 'State ID',
            'value'    => 'Value',
        ];
    }

    public function getProto()
    {
        return $this->hasOne('app\models\articles\proto\ArticleProto', array('id' => 'proto_id'));
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function syncronize()
    {
        $this->proto->syncronize($this, $this->state);
    }

}