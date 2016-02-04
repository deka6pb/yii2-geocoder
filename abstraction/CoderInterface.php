<?php

namespace deka6pb\geocoder\abstraction;

use deka6pb\geocoder\Point;

/**
 * Interface PointInterface
 * @package common\components\geo
 */
interface CoderInterface
{
    /**
     * @return mixed
     */
    public static function findByAddress($address, array $params = [], $results = 10);

    /**
     * @return mixed
     */
    public static function findOneByAddress($address, array $params = []);

    /**
     * @return mixed
     */
    public static function findByPoint(Point $point, $kind, Point $radius = null, array $params = [], $results = 10);

    /**
     * @return mixed
     */
    public static function findByOnePoint(Point $point, $kind, Point $radius = null, array $params = []);
}
