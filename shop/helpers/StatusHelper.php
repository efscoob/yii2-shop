<?php

namespace shop\helpers;


use shop\entities\user\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StatusHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_WAIT => 'Wait',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status):string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $label = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $label = 'label label-success';
                break;
            default:
                $label = 'label label-default';
        }
        return Html::tag('span', self::statusName($status), ['class' => $label]);
    }

}