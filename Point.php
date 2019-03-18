<?php


namespace deka6pb\geocoder;

use Location\Coordinate;
use Location\Distance\DistanceInterface;
use Location\Distance\Vincenty;

/**
 * Class Point
 * @package deka6pb\yii2-geocoder
 *
 * @property double $latitude
 * @property double $longitude
 */
class Point extends \yii\base\BaseObject
{
    /**
     * @var DistanceInterface
     */
    protected static $_calculator;

    /**
     * @var double
     */
    protected $latitude;

    /**
     * @var double
     */
    protected $longitude;

    /**
     * Returns latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns distance between points in meters
     *
     * @param Point $point
     * @param DistanceInterface $calculator
     *
     * @return float
     */
    public function getDistance(Point $point, DistanceInterface $calculator = null)
    {
        $calculator = $calculator ? : static::getCalculator();

        return $calculator->getDistance(
            new Coordinate($this->latitude, $this->longitude),
            new Coordinate($point->latitude, $point->longitude)
        );
    }

    /**
     * Format coordinates to string
     *
     * @return string
     */
    public function toString()
    {
        return  $this->longitude . ',' . $this->latitude;
    }

    /**
     * Returns calculator instance
     *
     * @return \Location\Distance\DistanceInterface
     */
    protected static function getCalculator()
    {
        if (static::$_calculator === null) {
            static::$_calculator = new Vincenty();
        }

        return static::$_calculator;
    }

}