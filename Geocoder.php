<?php

namespace deka6pb\geocoder;

use deka6pb\geocoder\coders\GoogleCoder;
use deka6pb\geocoder\coders\YandexCoder;
use yii\base\Exception;

class Geocoder
{
    const TYPE_GOOGLE = 1;
    const TYPE_YANDEX = 2;

    public static function build($type, $options = [])
    {
        switch($type) {
            case self::TYPE_GOOGLE:
                return new GoogleCoder($options);
                break;
            case self::TYPE_YANDEX:
                return new YandexCoder($options);
                break;
        }

        throw new Exception("Не указан тип геокодера");
    }
}
