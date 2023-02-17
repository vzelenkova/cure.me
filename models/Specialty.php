<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "specialty".
 *
 * @property int $id
 * @property string $name
 *
 * @property DoctorSpecialty[] $doctorSpecialties
 */
class Specialty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'specialty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[DoctorSpecialties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorSpecialties()
    {
        return $this->hasMany(DoctorSpecialty::class, ['specialty_id' => 'id']);
    }
}
