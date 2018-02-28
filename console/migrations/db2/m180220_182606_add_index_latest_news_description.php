<?php

use yii\db\Migration;

class m180220_182606_add_index_latest_news_description extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE latest_news ADD FULLTEXT INDEX idx_description (description)");
    }

    public function down()
    {
        $this->dropIndex('idx_description', 'latest_news');

        return false;
    }
}
