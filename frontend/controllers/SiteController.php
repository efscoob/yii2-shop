<?php
namespace frontend\controllers;

use shop\forms\NewsSearch;
use shop\services\news\NewsSearchService;
use shop\services\news\RssNewsService;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.`
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $newsService = new RssNewsService();
        $news = $newsService->getRandomNews();

        $form = new NewsSearch();
        $result = [];

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $searchService = new NewsSearchService();
                $result = $searchService->searchSphinx($form->keyword);
            } catch (\Exception $e) {
                echo $e->getMessage();die;
            }
        }

        return $this->render('index', [
            'title' => $news->title,
            'description' => $news->description,
            'model' => $form,
            'result' => $result,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
