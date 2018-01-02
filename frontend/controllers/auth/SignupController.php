<?php

namespace frontend\controllers\auth;


use shop\forms\auth\SignupForm;
use shop\services\auth\SignupService;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class SignupController extends Controller
{
    private $service;

    public function __construct($id, Module $module, SignupService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);
                Yii::$app->session->setFlash('success', 'Check email for further info');
                return $this->goHome();
            } catch(\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    public function actionConfirm(string $token)
    {
        try {
            $user = $this->service->confirm($token);
            Yii::$app->session->setFlash('success', 'Email was confirmed');
            if (Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('login');
    }
}