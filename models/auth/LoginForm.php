<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;

/**
 * Description of LoginForm
 *
 * @author ilya
 */
class LoginForm extends Model
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
     * @var boolean
     */
    public $rememberMe;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email', 'password'], 'required'],
            [['email'], 'string', 'max' => 256],
            [['password'], 'string'],
            [['email'], 'email'],
            [['rememberMe'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rememberMe' => 'Запомнить меня'
        ];
    }

    public function login()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $identity = Account::findIdentityByEmail($this->email);
        if (is_null($identity)) {
            $this->addError('email', 'Этот email не зарегистрирован');
            return false;
        }
        
        if (!$identity->passwordVerify($this->password)) {
            $this->addError('password', 'Неправильный пароль');
            return false;
        }
        
        $this->identity = $identity;
        return true;
    }

}
