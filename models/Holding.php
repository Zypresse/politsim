<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "holdings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property double $capital
 */
class Holding extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holdings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'state_id'], 'required'],
            [['state_id'], 'integer'],
            [['capital'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
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
            'state_id' => 'State ID',
            'capital' => 'Капитализация',
        ];
    }
    
    
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('holding_id' => 'id'))->orderBy('count DESC');
    }
    public function getSumStocks()
    {
        $sum = 0;
        foreach ($this->stocks as $stock) {
            $sum += $stock->count;
        }
        return $sum;
    }
}
