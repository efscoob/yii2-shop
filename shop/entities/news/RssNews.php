<?php

namespace shop\entities\news;


class RssNews implements NewsInterface
{
    private $jsonNewsPath;

    public function __construct($jsonNewsPath)
    {
        $this->jsonNewsPath = $jsonNewsPath;
    }

    public function getNewsList()
    {
        $news = json_decode(file_get_contents($this->jsonNewsPath));
        return $news;
    }
}