<?php

namespace shop\entities\news;

use yii\db\ActiveRecord;

/**
 * News model
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $link
 */

class News extends ActiveRecord implements NewsInterface
{

    public static function tableName()
    {
        return '{{%latest_news}}';
    }

    public function getNewsList()
    {
        // TODO: Implement getNewsList() method.
    }
}