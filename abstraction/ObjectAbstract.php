<?php

namespace deka6pb\geocoder\abstraction;

use deka6pb\geocoder\Point;
use yii\helpers\ArrayHelper;

abstract class ObjectAbstract extends \yii\base\Object
{

    const KIND_METRO = 'metro';
    const KIND_STREET = 'street';
    const KIND_HOUSE = 'house';
    const KIND_DISTRICT = 'district';
    const KIND_LOCALITY = 'locality';

    protected $type;

    protected $name;

    protected $address;

    protected $city;

    protected $text;

    protected $data = [];

    /**
     * Object point
     *
     * @var Point
     */
    protected $point;

    protected $description;

    /**
     * Returns type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $name
     * @param null $default
     * @return array|mixed
     */
    public function getData($name = null, $default = null)
    {
        if (is_string($name)) {
            return ArrayHelper::getValue($this->data, $name, $default);
        }
        return $this->data;
    }

    /**
     * @return Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    public function getLatitude()
    {
        return $this->point->getLatitude();
    }

    public function getLongitude()
    {
        return $this->point->getLongitude();
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isHouse()
    {
        return $this->type === self::KIND_HOUSE ? true : false;
    }

    public function isStreet()
    {
        return $this->type === self::KIND_STREET ? true : false;
    }

    public function __construct($config = [])
    {
        $this->initialization($config);
    }

    abstract function initialization($config = []);
}