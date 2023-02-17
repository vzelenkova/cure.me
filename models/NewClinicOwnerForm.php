<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\db\Transaction;
use Exception;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class NewClinicOwnerForm extends Model
{
    public $full_name;
    public $email;
    public $password;
    public $password_repeat;
    public $clinic_id;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'full_name' => 'ФИО',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }



    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'password_repeat', 'clinic_id'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @return ClinicOwner|bool
     */
    public function create()
    {
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {
            $user = new User();
            $user->full_name = $this->full_name;
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->access_token = Yii::$app->security->generateRandomString();
            $user->auth_key = Yii::$app->security->generateRandomString();

            if (!$user->save()) throw new Exception("Error in user creation");

            $owner = new ClinicOwner();
            $owner->user_id = $user->id;
            $owner->clinic_id = $this->clinic_id;

            if (!$owner->save()) throw new Exception("Error in clinic owner creation");
        } catch (Exception $e) {
            var_dump($e);
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return $owner;
    }
}
