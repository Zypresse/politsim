<?php

namespace app\models\politics\constitution;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Статья конституции
 *
 * @property integer $ownerType тип того к кому она привязана (государство/регион/агенство/пост) const из ConstitutionOwnerType
 * @property integer $ownerId ID Того к кому она привязана (государство/регион/агенство/пост)
 * @property integer $type тип статьи const из ConstitutionArticleType
 * @property integer $subType подтип статьи (например если статья про налог на продажу, то тут id прототипа ресурса)
 * @property string $value значение 
 * @property string $value2 дополнительное значение если понадобится в сложных статьях
 * @property string $value3 дополнительное значение если понадобится в очень сложных статьях
 */
class ConstitutionArticle extends MyActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutionsArticles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ownerType', 'ownerId', 'type', 'value'], 'required'],
            [['ownerType', 'ownerId', 'type', 'subType'], 'integer', 'min' => 0],
            [['value', 'value2', 'value3'], 'string'],
            [['ownerType', 'ownerId', 'type', 'subType'], 'unique', 'targetAttribute' => ['ownerType', 'ownerId', 'type', 'subType'], 'message' => 'The combination of Owner Type, Owner ID, Type and Sub Type has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ownerType' => Yii::t('app', 'Owner Type'),
            'ownerId' => Yii::t('app', 'Owner ID'),
            'type' => Yii::t('app', 'Type'),
            'subType' => Yii::t('app', 'Sub Type'),
            'value' => Yii::t('app', 'Value'),
            'value2' => Yii::t('app', 'Value2'),
            'value3' => Yii::t('app', 'Value3'),
        ];
    }

    public static function primaryKey()
    {
        return ['ownerType', 'ownerId', 'type', 'subType'];
    }

}
