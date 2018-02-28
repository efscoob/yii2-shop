<?php

$jsonFile = __DIR__ . "/rss-links.json";

$links = json_decode(file_get_contents($jsonFile), true);
if (!$links) {
    die('JSON load error');
}

$config = include(__DIR__ . "/config/db.php");

try {
    $db = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
} catch (PDOException $e) {
    echo 'DB connect error: ' . $e->getMessage();
}

$news = [];

foreach ($links as $key => $link) {

    if (!$sxml = simplexml_load_file($link)) {
        echo 'RSS load error';
        continue;
    }
    $title = strip_tags($sxml[0]->channel->item[0]->title);
    $desc = strip_tags($sxml[0]->channel->item[0]->description);
    $newslink = strip_tags($sxml[0]->channel->item[0]->link);

    echo $title . "\r\n";

    $news[] = ['title' => $title, 'description' => $desc];

    $sql = 'INSERT INTO latest_news (title, description, link) VALUES(:title, :descr, :link)';

    try {
        $st = $db->prepare($sql);
        $st->bindParam(':title', $title);
        $st->bindParam(':descr', $desc);
        $st->bindParam(':link', $newslink);
        $st->execute();
    } catch (PDOException $e) {
        echo 'DB execute error: ' . $e->getMessage();
    }
}

if ($news[0]['title']) {
    file_put_contents(__DIR__ . "/news-current.json", json_encode($news));
}