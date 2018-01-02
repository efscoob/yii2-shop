<?php

namespace frontend\controllers\auth;


use shop\forms\auth\LoginForm;
use shop\services\auth\AuthService;
use yii\base\Module;
use yii\web\Controller;
use Yii;

class AuthController extends Controller
{
    private $service;

    public function __construct($id, Module $module, AuthService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                return $this->goBack();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Wrong username or password. Try again.');
                Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}