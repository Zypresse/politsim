<?php

namespace app\models\politics\bills;

use Yii,
    app\models\politics\AgencyPost,
    app\models\base\MyActiveRecord;

/**
 * Голос по законопроекту
 *
 * @property integer $billId
 * @property integer $postId
 * @property integer $variant
 */
class BillVote extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billsVotes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['billId', 'postId', 'variant'], 'required'],
            [['billId', 'postId', 'variant'], 'integer', 'min' => 0],
            [['billId', 'postId'], 'unique', 'targetAttribute' => ['billId', 'postId'], 'message' => 'The combination of Bill ID and Post ID has already been taken.'],
            [['billId'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::className(), 'targetAttribute' => ['billId' => 'id']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['postId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'billId' => Yii::t('app', 'Bill ID'),
            'postId' => Yii::t('app', 'Post ID'),
            'variant' => Yii::t('app', 'Variant'),
        ];
    }
    
    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['id' => 'billId']);
    }
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
    }
    
}
