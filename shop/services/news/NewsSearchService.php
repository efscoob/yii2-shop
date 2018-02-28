<?php

namespace shop\services\news;

use Yii;
use shop\entities\news\News;
use yii\helpers\ArrayHelper;

class NewsSearchService
{
    private $news;

    /**
     * @param string $keyword
     * @return array
     */
    public function searchSphinx(string $keyword): array
    {
        $sql = "SELECT * FROM idx_latest_news_description WHERE MATCH('$keyword') OPTION ranker=WORDCOUNT";
        $data = Yii::$app->sphinx->createCommand($sql)->queryAll();

        $ids = ArrayHelper::map($data, 'id', 'id');
        $this->news = News::find()->where(['id' => $ids])->asArray()->limit(100)->all(Yii::$app->db2);
        $this->news = ArrayHelper::index($this->news, 'id');

        $result = [];

        foreach ($ids as $id) {
            if ($this->uniqeNews($id)) {
                $result[] = $this->news[$id];
            }
        }
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';die;

        return $result;
    }

    /**
     * Remove news if her title equals $this->news[$id]['title']
     *
     * @param $id
     * @return bool
     */
    private function uniqeNews($id): bool
    {
        if (empty($this->news[$id])) {
            return false;
        }
        foreach ($this->news as $item) {
            if ($item['id'] == $id) {
                continue;
            }
            if ($item['title'] == $this->news[$id]['title']) {
                $num = $item['id'];
                unset($this->news[$num]);
            }

        }

        return true;
    }
}