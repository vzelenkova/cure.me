<?php

namespace app\modules\admin\controllers;

use app\models\Clinic;
use app\models\Doctor;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAdmin()) {
            $this->redirect("/");
        }

        return parent::beforeAction($action);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $clinics_amount = Clinic::find()->count();
        $doctors_amount = Doctor::find()->count();
        return $this->render('index', compact('clinics_amount', 'doctors_amount'));
    }
}
