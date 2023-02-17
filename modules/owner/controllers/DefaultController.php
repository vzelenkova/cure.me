<?php

namespace app\modules\owner\controllers;

use yii\web\Controller;
use app\models\DoctorClinic;
use Yii;

/**
 * Default controller for the `owner` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $clinic = Yii::$app->user->identity->getClinicOwner()->one()->clinic;
        $doctor_amount = DoctorClinic::find()->where(['clinic_id' => $clinic->id])->count();

        return $this->render('index', [
            'model' => $clinic,
            'doctor_amount' => $doctor_amount
        ]);
    }
}
