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
    
    const TYPE_GOOGLE = 1;
    const TYPE_FACEBOOK = 2;
    const TYPE_VKONTAKTE = 3;
    
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
            [['sourceId', 'sourceType'], 'unique', 'targetAttribute' => ['sourceId', 'sourceType']],
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
    
    /**
     * 
     * @param string $type
     * @return integer
     */
    public static function typeToId($type)
    {
        return [
            'google' => self::TYPE_GOOGLE,
            'facebook' => self::TYPE_FACEBOOK,
            'vkontakte' => self::TYPE_VKONTAKTE,
        ][$type];
    }
    
    public static function primaryKey()
    {
        return ['sourceId', 'sourceType'];
    }
    
    /**
     * Регистрирует новый аккаунт
     * @param integer $sourceType
     * @param array $params
     * @return Account
     */
    public static function signUp($sourceType, $params)
    {
        
        $account = new Account($params);
        $account->generateAccessToken();
        $transaction = $account->getDb()->beginTransaction();
        if ($account->save()) {
            $accountProvider = new self([
                'accountId' => $account->id,
                'sourceType' => $sourceType,
                'sourceId' => (string) $attributes['id'] ?: $attributes['uid'],
            ]);
            if ($accountProvider->save()) {
                $transaction->commit();
                Yii::$app->user->login($account, 30*24*60*60);
            }
        }
        
        return $account;
    }
    
    /**
     * 
     * @param integer $sourceType
     * @param array $attributes
     * @return array
     */
    public static function loadParams($sourceType, $attributes)
    {
        switch ($sourceType) {
            case static::TYPE_GOOGLE:
                return [
                    'email' => $attributes['id'].'@google', // TODO
                ];
            case static::TYPE_FACEBOOK:
                return [
                    'email' => $attributes['id'].'@facebook', // TODO
                ];
            case static::TYPE_VKONTAKTE:
                return [
                    'email' => $attributes['email'],
                ];
        }
    }
    
}
