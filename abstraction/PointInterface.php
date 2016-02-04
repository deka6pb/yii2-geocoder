<?php

namespace deka6pb\geocoder\abstraction;

use deka6pb\geocoder\Point;

/**
 * Interface PointInterface
 * @package common\components\geo
 */
interface PointInterface
{

    /**
     * Returns Point object
     *
     * @return \common\services\yandex\geo\Point
     */
    public function getPoint();

    /**
     * @param Point $point
     *
     * @return void
     */
    public function setPoint(Point $point);

}
