<?php

namespace deka6pb\geocoder\objects;

use deka6pb\geocoder\abstraction\ObjectAbstract;
use deka6pb\geocoder\Point;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class GoogleObject extends ObjectAbstract
{
    public function initialization($config = [])
    {
        if (false === is_array($config)) {
            $config = ArrayHelper::toArray($config);
        }

        $point = ArrayHelper::getValue($config, 'geometry');
        $addressComponents = ArrayHelper::getValue($config, 'address_components');
        $location = ArrayHelper::getValue($point, 'location');
        $types = ArrayHelper::getValue($config, 'types');

        foreach($addressComponents AS $component) {
            if(in_array('street_number', $component['types'])) {
                $this->data['houseNumber'] = ArrayHelper::getValue($component, 'long_name');
            }
            // Страну
            if(in_array('country', $component['types'])) {
                $this->data['country'] = ArrayHelper::getValue($component, 'long_name');
                $this->data['countrySlug'] = ArrayHelper::getValue($component, 'short_name');
            }
            // Штат
            if(in_array('administrative_area_level_1', $component['types'])) {
                $this->data['area'] = ArrayHelper::getValue($component, 'long_name');
            }
            // Город
            if(in_array('locality', $component['types'])) {
                $this->data['city'] = ArrayHelper::getValue($component, 'long_name');
                $this->city = ArrayHelper::getValue($component, 'long_name');
            }
            // Улица
            if(in_array('route', $component['types'])) {
                $this->data['street'] = ArrayHelper::getValue($component, 'long_name');
                $this->street = ArrayHelper::getValue($component, 'long_name');
            }
            // Дом
            if(in_array('street_number', $component['types'])) {
                $this->data['house'] = ArrayHelper::getValue($component, 'long_name');
                $this->house = ArrayHelper::getValue($component, 'long_name');
            }
        }

        // Всегда ставим город
        $this->locality_type = self::LOCALITY_TYPE_CITY;

        // Получаем полный адрес
        $this->address = ArrayHelper::getValue($config, 'formatted_address');

        // Тип объекта
        $this->type = in_array('premise', $types) ||
                        in_array('premise', $types) ||
                        in_array('subpremise', $types)
            ? self::KIND_HOUSE
            : self::KIND_STREET;

        $latitude = ArrayHelper::getValue($location, 'lat');
        $longitude = ArrayHelper::getValue($location, 'lng');

        $this->point = new Point(['latitude' => $latitude, 'longitude' => $longitude]);
    }
}