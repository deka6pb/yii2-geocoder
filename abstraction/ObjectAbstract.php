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
     * ObjectAbstract constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct();

        $this->initialization($config);
    }

    /**
     * Initialization by config
     *
     * @param array $config
     * @return mixed
     */
    abstract function initialization($config = []);

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

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->point->getLatitude();
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->point->getLongitude();
    }

    /**
     * @return mixed
     */
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

    /**
     * @return bool
     */
    public function isStreet()
    {
        return $this->type === self::KIND_STREET ? true : false;
    }
}