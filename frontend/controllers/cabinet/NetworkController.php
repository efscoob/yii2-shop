<?php

namespace frontend\controllers\cabinet;


use shop\services\auth\NetworkService;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\base\Controller;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class NetworkController extends Controller
{
    private $service;

    public function __construct($id, Module $module, NetworkService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actions()
    {
        return [
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index']),
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');
        try {
            $this->service->attach(\Yii::$app->user->id, $identity, $network);
            \Yii::$app->session->setFlash('success', 'Network attached successfully');
        } catch(\DomainException $e) {
            \Yii::$app->errorHandler->logException($e);
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}