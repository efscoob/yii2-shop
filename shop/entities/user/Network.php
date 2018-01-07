<?php

namespace shop\entities\user;


use Webmozart\Assert\Assert;
use yii\db\ActiveRecord;

/**
 * Class Network
 * @package shop\entities\user
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 */
class Network extends ActiveRecord
{
    public static function create($identity, $network): self
    {
        Assert::notEmpty($identity);
        Assert::notEmpty($network);

        $item = new static();
        $item->identity = $identity;
        $item->network = $network;

        return $item;
//        $item = new static();
//        $item->identity = $identity;
//        $item->network = $network;
//
//        return $item;
    }

    public static function tableName()
    {
        return '{{%user_networks}}';
    }
}