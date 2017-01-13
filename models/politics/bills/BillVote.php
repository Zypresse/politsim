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
 * 
 * @property Bill $bill
 * @property AgencyPost $post
 * 
 */
class BillVote extends MyActiveRecord
{
    
    const VARIANT_PLUS = 1;
    const VARIANT_ABSTAIN = 2;
    const VARIANT_MINUS = 3;
    
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
    
    public static function primaryKey()
    {
        return ['billId', 'postId'];
    }
    
    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['id' => 'billId']);
    }
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->bill->addVote($this->variant);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
}
