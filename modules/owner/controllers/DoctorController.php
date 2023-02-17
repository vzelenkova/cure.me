<?php

namespace app\modules\owner\controllers;

use app\models\Doctor;
use app\models\DoctorClinic;
use app\models\NewDoctorForm;
use app\models\DoctorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * DoctorController implements the CRUD actions for Doctor model.
 */
class DoctorController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isOwner()) {
            $this->redirect("/");
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Doctor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $clinic_id = Yii::$app->user->identity->getClinicOwner()->one()->clinic_id;
        $doctor_ids = array_column(DoctorClinic::findAll(['clinic_id' => $clinic_id]), 'doctor_id');
        $searchModel = Doctor::find()->where(["id" => $doctor_ids]);

        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctor model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new NewDoctorForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $doctor = $model->create();
                if ($doctor) {
                    $clinic_id = Yii::$app->user->identity->getClinicOwner()->one()->clinic_id;
                    $dc = new DoctorClinic();
                    $dc->doctor_id = $doctor->id;
                    $dc->clinic_id = $clinic_id;
                    $dc->save();

                    return $this->redirect(['view', 'id' => $doctor->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Doctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldAvatar = $model->avatar;

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->upload = UploadedFile::getInstance($model, 'avatar');
            if ($model->upload) $model->avatar = $model->uploadAvatar();
            else $model->avatar = $oldAvatar;

            $specialties = $this->request->post()['specialties'];
            $model->replaceDoctorSpecialties($specialties);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Doctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctor::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
