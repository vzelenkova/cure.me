<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\imagine\Image;

/**
 * This is the model class for table "clinic".
 *
 * @property int $id
 * @property string $address
 * @property string|null $geo
 * @property string $name
 * @property string|null $phone
 * @property string|null $schedule
 *
 * @property ClinicOwner[] $clinicOwners
 * @property DoctorClinic[] $doctorClinics
 * @property Doctor[] $doctors
 */
class Clinic extends \yii\db\ActiveRecord
{
    public $upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clinic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'name'], 'required'],
            [['address'], 'string'],
            [['geo', 'name', 'phone', 'schedule'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Адрес',
            'geo' => 'Геопозиция',
            'name' => 'Наименование клиники',
            'phone' => 'Телефон',
            'schedule' => 'Расписание',
            'avatar' => 'Изображение клиники',
        ];
    }

    /**
     * Gets query for [[ClinicOwners]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinicOwners()
    {
        return $this->hasMany(ClinicOwner::class, ['clinic_id' => 'id']);
    }

    /**
     * Gets query for [[DoctorClinics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorClinics()
    {
        return $this->hasMany(DoctorClinic::class, ['clinic_id' => 'id']);
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctors()
    {
        $doctor_ids = $this->getDoctorClinics()->select(["doctor_id"])->asArray()->all();
        $doctor_ids = array_map(function ($e) {
            return $e["doctor_id"];
        }, $doctor_ids);

        return Doctor::findAll($doctor_ids);
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


    public function renderAvatar($options = ['style' => 'width: 400px; height: 400px; object-fit: cover'])
    {
        if (!empty($this->avatar)) {
            $img = Yii::getAlias('@webroot') . '/images/' . $this->tableName() . '/source/' .  $this->avatar;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/' . $this->tableName() . '/source/' .  $this->avatar;
                echo Html::img($url, $options);
            }
        }
    }
}
