<?php

namespace app\controllers;

use Yii;

use app\models\LoginForm;

class AuthController extends \yii\web\Controller
{
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\RegisterForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $user = $model->register();
                if ($user) {
                    $this->redirect($user->getHomeUrl(), 303);
                }
            }
        }

        $model->password = '';
        $model->password_repeat = '';
        return $this->render('register', [
            'model' => $model,
        ]);
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
