<?php

namespace app\models\auth;

use Yii;
use app\models\base\ActiveRecord;

/**
 * OAuth-аккаунты. Таблица "accountProviders".
 *
 * @property integer $accountId
 * @property integer $sourceId
 * @property integer $sourceType
 *
 * @property Account $account
 */
class AccountProvider extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountProviders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountId', 'sourceId', 'sourceType'], 'required'],
            [['accountId', 'sourceId', 'sourceType'], 'default', 'value' => null],
            [['accountId', 'sourceId', 'sourceType'], 'integer'],
            [['accountId', 'sourceId', 'sourceType'], 'unique', 'targetAttribute' => ['accountId', 'sourceId', 'sourceType']],
            [['accountId'], 'exist', 'skipOnError' => false, 'targetClass' => Account::className(), 'targetAttribute' => ['accountId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accountId' => 'Account ID',
            'sourceId' => 'Source ID',
            'sourceType' => 'Source Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'accountId']);
    }
}
