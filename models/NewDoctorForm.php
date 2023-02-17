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
class NewDoctorForm extends Model
{
    public $full_name;
    public $email;
    public $password;
    public $password_repeat;
    public $specialties;
    public $avatar;
    public $description;


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
            'specialties' => 'Специальности врача',
            'avatar' => 'Аватар',
            'description' => 'Описание',
        ];
    }



    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'password_repeat', 'specialties', 'avatar'], 'required'],
            ['email', 'email'],
            ['specialties', 'each', 'rule' => ['string']],
            ['avatar', 'image', 'extensions' => 'png, jpg, gif'],
            ['description', 'string'],
        ];
    }





    /**
     * @return Doctor|bool
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

            $doctor = new Doctor();
            $doctor->upload = UploadedFile::getInstance($this, 'avatar');
            $doctor->user_id = $user->id;
            $doctor->description = $this->description;

            if (!$doctor->save()) throw new Exception("Error in doctor creation");

            foreach ($this->specialties as $id) {
                $ds = new DoctorSpecialty();
                $ds->doctor_id = $doctor->id;
                $ds->specialty_id = $id;
                $ds->save(false);
            }
        } catch (Exception $e) {
            var_dump($e);
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return $doctor;
    }
}
