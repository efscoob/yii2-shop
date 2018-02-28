<?php

namespace shop\forms;


use yii\base\Model;

class NewsSearch extends Model
{
    public $keyword;

    public function rules()
    {
        return [
            ['keyword', 'required'],
        ];
    }
}