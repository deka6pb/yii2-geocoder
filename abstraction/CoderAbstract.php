<?php

namespace deka6pb\geocoder\abstraction;

use deka6pb\geocoder\Point;

abstract class CoderAbstract extends \yii\base\Object implements CoderInterface
{
    /**
     * Максимальное кол-во попыток получить данные
     */
    const MAX_ATTEMPT = 5;

    public static function findByAddress($address, array $params = [], $results = 10)
    {
        if (false === is_string($address)) {
            throw new \InvalidArgumentException('Address must be a string');
        }

        return static::getData($address, compact('results'));
    }

    public static function findOneByAddress($address, array $params = [])
    {
        return static::findByAddress($address, $params, 1);
    }

    public static function findByPoint(Point $point, $kind, Point $radius = null, array $params = [], $results = 10)
    {
        if ($radius) {
            $params['spn'] = $radius->toString();
        }

        return static::getData($point->toString(), array_merge(compact('kind', 'results'), $params));
    }

    public static function findByOnePoint(Point $point, $kind, Point $radius = null, array $params = [])
    {
        return static::findByPoint($point, $kind, $radius, $params, 1);
    }

    private static function getData($query, array $params)
    {
        $objects = null;

        for ($i = 0; $i < self::MAX_ATTEMPT; $i++) {
            $objects = static::execute($query, $params);

            if (!is_null($objects)) {
                break;
            }
        }

        return $objects;
    }

    abstract protected static function execute($query, array $params = []);
}