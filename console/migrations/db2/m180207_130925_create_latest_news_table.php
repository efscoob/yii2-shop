<?php

use yii\db\Migration;

/**
 * Handles the creation of table `latest_news`.
 */
class m180207_130925_create_latest_news_table extends Migration
{
//    public function init()
//    {
//        $this->db = 'db2';
//        parent::init();
//    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%latest_news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'link' => $this->string()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('latest_news');
    }
}
