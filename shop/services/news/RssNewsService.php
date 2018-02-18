<?php

namespace shop\services\news;


use shop\entities\news\RssNews;

class RssNewsService
{
    public function getRandomNews()
    {
        $news = \Yii::$container->get(RssNews::class);
        $newsList = $news->getNewsList();
        $newsNum = array_rand($newsList);
        return $newsList[$newsNum];
    }
}