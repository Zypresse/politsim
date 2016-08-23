<?php

namespace app\models\bills;

use app\components\MyModel,
    app\models\bills\Bill,
    app\models\Post;

/**
 * Голос по законопроекту. Таблица "bills_votes".
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
        return 'bills_votes';
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
            'id'      => 'ID',
            'bill_id' => 'Bill ID',
            'post_id' => 'Post ID',
            'variant' => '1 - да, 2 - нет, 0 - воздержался (или номер варианта, при выборе)',
        ];
    }

    public function getBill()
    {
        return $this->hasOne(Bill::className(), array('id' => 'bill_id'));
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), array('id' => 'post_id'));
    }

}
