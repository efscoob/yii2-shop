<?php

namespace frontend\controllers;


use shop\forms\ContactForm;
use shop\services\ContactService;
use yii\base\Module;
use yii\web\Controller;
use Yii;

class ContactController extends Controller
{
    private $service;

    public function __construct($id, Module $module, ContactService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        //$this->service = Yii::$container->get(ContactService::class);
        $this->service = $service;
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Email send to our support successfully.');
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash("error", $e->getMessage());
            }

            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }
}