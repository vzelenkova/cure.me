<?php

namespace app\models;

use Yii;
use yii\imagine\Image;
use yii\helpers\Html;

/**
 * This is the model class for table "doctor".
 *
 * @property int $user_id
 *
 * @property DoctorSpecialty[] $doctorSpecialties
 * @property User $user
 */
class Doctor extends \yii\db\ActiveRecord
{
    /**
     * Вспомогательный атрибут для загрузки изображения товара
     */
    public $upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor';
    }

    public static function search2($term, $limit, $searchAttributes)
    {
        $doctors = Doctor::find()
            ->joinWith('user')
            ->where(['like', 'user.full_name', $term])
            ->distinct()
            ->limit($limit)
            ->asArray()
            ->all();
        return array_map(
            function ($e) {
                return array_merge($e["user"], $e);
            },
            $doctors
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['avatar', 'image', 'extensions' => 'png, jpg, gif'],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'ID',
            'avatar' => 'Аватар',
            'description' => 'Описание',
        ];
    }

    /**
     * Gets query for [[DoctorSpecialties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorSpecialties()
    {
        return $this->hasMany(DoctorSpecialty::class, ['doctor_id' => 'id']);
    }

    public function replaceDoctorSpecialties($ids)
    {
        DoctorSpecialty::deleteAll(["doctor_id" => $this->id]);
        foreach ($ids as $id) {
            $ds = new DoctorSpecialty();
            $ds->doctor_id = $this->id;
            $ds->specialty_id = $id;
            $ds->save(false);
        }
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorClinic()
    {
        return $this->hasOne(DoctorClinic::class, ['id' => 'doctor_id']);
    }


    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinics()
    {
        $clinic_ids = DoctorClinic::findAll(['doctor_id' => $this->id]);
        return Clinic::findAll($clinic_ids);
    }

    public function uploadAvatar()
    {
        if ($this->upload) {
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            $source = Yii::getAlias('@webroot/images/' . self::tableName() . '/source/' . $name);
            if (!file_exists(dirname($source))) mkdir(dirname($source), 0777, true);
            if ($this->upload->saveAs($source)) {
                $large = Yii::getAlias('@webroot/images/' . self::tableName() . '/large/' . $name);
                if (!file_exists(dirname($large))) mkdir(dirname($large), 0777, true);
                Image::thumbnail($source, 1000, 1000)->save($large, ['quality' => 100]);
                $medium = Yii::getAlias('@webroot/images/' . self::tableName() . '/medium/' . $name);
                if (!file_exists(dirname($medium))) mkdir(dirname($medium), 0777, true);
                Image::thumbnail($source, 500, 500)->save($medium, ['quality' => 95]);
                $small = Yii::getAlias('@webroot/images/' . self::tableName() . '/small/' . $name);
                if (!file_exists(dirname($small))) mkdir(dirname($small), 0777, true);
                Image::thumbnail($source, 250, 250)->save($small, ['quality' => 90]);
                return $name;
            }
        }
        return false;
    }

    public static function removeImage($name)
    {
        if (!empty($name)) {
            $source = Yii::getAlias('@webroot/images/' . self::tableName() . '/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $large = Yii::getAlias('@webroot/images/' . self::tableName() . '/large/' . $name);
            if (is_file($large)) {
                unlink($large);
            }
            $medium = Yii::getAlias('@webroot/images/' . self::tableName() . '/medium/' . $name);
            if (is_file($medium)) {
                unlink($medium);
            }
            $small = Yii::getAlias('@webroot/images/' . self::tableName() . '/small/' . $name);
            if (is_file($small)) {
                unlink($small);
            }
        }
    }

    /**
     * Удаляет изображение при удалении товара
     */
    public function afterDelete()
    {
        parent::afterDelete();
        self::removeImage($this->avatar);
    }

    public function renderAvatar($options = ['style' => 'width: 200px; height: 200px; object-fit: cover', 'class' => 'py-2'])
    {
        if (!empty($this->avatar)) {
            $img = Yii::getAlias('@webroot') . '/images/doctor/source/' .  $this->avatar;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/doctor/source/' .  $this->avatar;
                echo Html::img($url, $options);
            }
        }
    }
}
