<?php

namespace frontend\helpers;


class HighlightHelper
{
    static public function boldKeyword(string $keyword, string $text): string
    {
        $text = str_replace($keyword, '<b>' . $keyword . '</b>', $text);
        return $text;
    }
}