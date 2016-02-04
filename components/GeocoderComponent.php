<?php

namespace deka6pb\geocoder\components;

use deka6pb\geocoder\abstraction\CoderInterface;
use deka6pb\geocoder\Geocoder;
use deka6pb\geocoder\Point;
use yii\base\Component;

/**
 * Class GeocoderComponent
 * @package common\components
 */
class GeocoderComponent extends Component
{
    /**
     * @var CoderInterface
     */
    private $_geocoder;

    /**
     * @param null $language
     * @return \deka6pb\geocoder\coders\GoogleCoder|\deka6pb\geocoder\coders\YandexCoder
     * @throws \yii\base\Exception
     */
    public function init($language = null)
    {
        $language = !empty($language) ? $language :  \Yii::$app->language;
        switch($language) {
            case "ru":
                $this->_geocoder = Geocoder::build(Geocoder::TYPE_YANDEX);
                break;
            case "en":
                $this->_geocoder = Geocoder::build(Geocoder::TYPE_GOOGLE);
                break;
        }

        $this->_geocoder = Geocoder::build(Geocoder::TYPE_YANDEX);
    }

    /**
     * @return CoderInterface
     */
    public function getGeocoder()
    {
        return $this->_geocoder;
    }

    /**
     * @param $address
     * @param array $params
     * @param int $results
     * @return mixed
     */
    public function findByAddress($address, array $params = [], $results = 10)
    {
        return $this->getGeocoder()->findByAddress($address, $params, $results);
    }

    /**
     * @param $address
     * @param array $params
     * @return mixed
     */
    public function findOneByAddress($address, array $params = [])
    {
        return $this->getGeocoder()->findByAddress($address, $params, 1);
    }

    /**
     * @param Point $point
     * @param $kind
     * @param Point|null $radius
     * @param array $params
     * @param int $results
     * @return mixed
     */
    public function findByPoint(Point $point, $kind, Point $radius = null, array $params = [], $results = 10)
    {
        return $this->getGeocoder()->findByPoint($point, $kind, $radius, $params, $results);
    }

    /**
     * @param Point $point
     * @param $kind
     * @param Point|null $radius
     * @param array $params
     * @return mixed
     */
    public function findByOnePoint(Point $point, $kind, Point $radius = null, array $params = [])
    {
        return $this->getGeocoder()->findByOnePoint($point, $kind, $radius, $params);
    }

}
