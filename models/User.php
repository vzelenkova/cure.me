<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $access_token
 * @property string $auth_key
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'password'], 'required'],
            [['full_name', 'email', 'password'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'ФИО',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public function isAdmin()
    {
        return !is_null($this->getAdmin()->one());
    }

    public function isDoctor()
    {
        return !is_null($this->getDoctor()->one());
    }

    public function isOwner()
    {
        return !is_null($this->getClinicOwner()->one());
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }


    public function getHomeUrl()
    {
        if ($this->isAdmin()) return "/admin/";
        return "/";
    }

    public function getDoctor()
    {
        return $this->hasMany(Doctor::class, ['user_id' => 'id']);
    }

    public function getAdmin()
    {
        return $this->hasMany(Admin::class, ['user_id' => 'id']);
    }

    public function getClinicOwner()
    {
        return $this->hasMany(ClinicOwner::class, ['user_id' => 'id']);
    }
}
