<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\RegisterForm;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->register()) {
                $login_model = new LoginForm();
                $login_model->username = $model->username;
                $login_model->password = $model->password;
                $login_model->rememberMe = $model->rememberMe;

                if ($login_model->login()) {
                    return $this->redirect(['site/home']);
                }

                return $this->redirect(['auth/login']);
            }
        }

        return $this->render('register', ['model' => $model]);
    }
}