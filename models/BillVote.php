<?php

namespace app\models;

use app\components\MyModel;

/**
 * Голос по законопроекту. Таблица "bill_votes".
 *
 * @property integer $id
 * @property integer $bill_id
 * @property integer $post_id
 * @property integer $variant Выбранный вариант: 1 - да, 2 - нет, 0 - воздержался (или номер варианта, при выборе)
 * 
 * @property Bill $bill Законопроект
 * @property Post $post Пост
 */
class BillVote extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_id', 'post_id', 'variant'], 'required'],
            [['bill_id', 'post_id', 'variant'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bill_id' => 'Bill ID',
            'post_id' => 'Post ID',
            'variant' => '1 - да, 2 - нет, 0 - воздержался (или номер варианта, при выборе)',
        ];
    }
    
    public function getBill()
    {
        return $this->hasOne('app\models\Bill', array('id' => 'bill_id'));
    }
    public function getPost()
    {
        return $this->hasOne('app\models\Post', array('id' => 'post_id'));
    }
}