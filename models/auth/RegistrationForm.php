<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;

/**
 * Description of RegistrationForm
 *
 * @author ilya
 */
class RegistrationForm extends Model
{

    /**
     *
     * @var Account
     */
    public $identity = null;
    
    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $passwordConfirm;

    /**
     *
     * @var boolean
     */
    public $agreeTerms;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email', 'password', 'passwordConfirm', 'agreeTerms'], 'required'],
            [['email'], 'string', 'max' => 256],
            [['password', 'passwordConfirm'], 'string'],
            [['email'], 'unique', 'targetClass' => Account::class, 'message' => Yii::t('app', 'Этот email уже занят.')],
            [['email'], 'email'],
            [['passwordConfirm'], 'repeatedPassword'],
            [['agreeTerms'], 'isTrue'],
        ];
    }

    public function repeatedPassword($attribute, $params = [])
    {
        if ($this->$attribute !== $this->password) {
            $this->addError($attribute, Yii::t('app', 'Пароли не совпадают', [
                        $this->getAttributeLabel($attribute),
                        $this->getAttributeLabel('password'),
            ]));
            return false;
        }
        return true;
    }

    public function isTrue($attribute, $params = [])
    {
        if (!$this->$attribute) {
            $this->addError($attribute, Yii::t('app', 'Нажми на квадратик, сука'));
            return false;
        }
        return true;
    }

    public function attributeLabels()
    {
        return [
            'agreeTerms' => 'Я не пидор'
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $identity = new Account([
            'email' => $this->email,
        ]);
        $identity->setPassword($this->password);
        $identity->generateAccessToken();
        $identity->save();
        $this->identity = $identity;
        return true;
    }

}
