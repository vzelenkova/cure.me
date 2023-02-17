<?php

namespace app\controllers;

use app\models\Appointment;
use app\models\Clinic;
use app\models\Doctor;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use pozitronik\widgets\SearchWidget;
use yii\data\Pagination;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            // 'search' => SearchAction::class,
            // 'search' => [
            //     'class' => 'pozitronik\widgets\SearchAction',
            // ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSearch()
    {
        return json_encode(
            SearchWidget::Search(
                '\\app\\models\\' . $_GET['alias'],
                $_GET['term'],
                SearchWidget::DEFAULT_LIMIT,
                null,
                $_GET['alias'] == 'Doctor' ? 'search2' : SearchWidget::DEFAULT_METHOD,
            )
        );
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $doctors = Doctor::find()->limit(3)->all();
        $clinics = Clinic::find()->limit(3)->all();

        return $this->render('index', compact('clinics', 'doctors'));
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionAppointment($doctor)
    {
        $model = new \app\models\Appointment();
        $model->doctor_id = $doctor;
        $model->user_id = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->when = date('Y-m-d H:i:s', strtotime($model->when));
                $model->save();
                return $this->redirect(['appointments']);
            }
        }

        return $this->render('appointment', [
            'model' => $model,
        ]);
    }

    public function actionAppointments()
    {
        $user = Yii::$app->user->identity;
        if (is_null($user)) {
            Yii::$app->session->setFlash('error', ['Войдите в аккаунт прежде чем смотреть записи']);
            $this->goHome();
            return;
        }

        $query = Appointment::find()->where(['user_id' => Yii::$app->user->identity->id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('appointments', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionDoctorAppointments()
    {
        $user = Yii::$app->user->identity;
        if (is_null($user)) {
            Yii::$app->session->setFlash('error', ['Войдите в аккаунт прежде чем смотреть записи']);
            $this->goHome();
            return;
        }

        $query = Appointment::find()->where(['doctor_id' => Yii::$app->user->identity->getDoctor()->one()->id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('doctor_appointments', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }


    public function actionDoctors()
    {
        $query = Doctor::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('doctors', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionDoctor($id)
    {
        $model = Doctor::findOne($id);

        return $this->render('doctor', [
            'model' => $model,
        ]);
    }


    public function actionClinics()
    {
        $query = Clinic::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('clinics', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }


    public function actionClinic($id)
    {
        $model = Clinic::findOne($id);

        return $this->render('clinic', [
            'model' => $model,
        ]);
    }
}
