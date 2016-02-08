<?php

namespace deka6pb\geocoder\abstraction;

use deka6pb\geocoder\Point;

/**
 * Interface PointInterface
 * @package deka6pb\yii2-geocoder
 */
interface PointInterface
{

    /**
     * Returns Point object
     *
     * @return Point
     */
    public function getPoint();

    /**
     * @param Point $point
     *
     * @return void
     */
    public function setPoint(Point $point);

}
