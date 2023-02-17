<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class RegisterForm extends Model
{
    public $full_name;
    public $email;
    public $password;
    public $password_repeat;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'password_repeat'], 'trim'],
            [
                'email',
                'unique',
                'targetClass' => User::class,
            ],
            // username and password are both required
            [['full_name', 'email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['password', 'password_repeat'], 'string', 'min' => 8],
            // поле password_repeat должно совпадать с password
            ['password_repeat', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'full_name' => 'ФИО',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль ещё раз',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return User|bool whether the user is logged in successfully
     */
    public function register()
    {
        if (!$this->validate()) return false;

        $user = new User();
        $user->full_name = $this->full_name;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->access_token = Yii::$app->security->generateRandomString();
        $user->auth_key = Yii::$app->security->generateRandomString();

        if (!$user->insert()) return false;

        Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        return $user;
    }
}
