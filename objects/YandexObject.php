<?php

namespace deka6pb\geocoder\objects;

use deka6pb\geocoder\abstraction\ObjectAbstract;
use deka6pb\geocoder\Point;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class YandexObject extends ObjectAbstract
{
    public function initialization($config = [])
    {
        if (false === is_array($config)) {
            $config = ArrayHelper::toArray($config);
        }

        $object = ArrayHelper::getValue($config, 'GeoObject');

        $point = ArrayHelper::getValue($object, 'Point.pos', '');
        $point = explode(' ', $point);

        $metaData = ArrayHelper::getValue($object, 'metaDataProperty.GeocoderMetaData', []);

        // Получаем полный адрес
        $this->address = ArrayHelper::getValue($metaData, 'text');
        // Получаем город
        $this->data['city'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName');
        $this->city = $this->data['city'];
        // Получаем округ/область
        $this->data['area'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.AdministrativeAreaName');
        // Страну
        $this->data['country'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryName');
        // Код страны
        $this->data['countrySlug'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryCode');
        // Получаем ветку/улицу
        $this->data['thoroughfare'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName');
        $this->data['street'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName');
        // Получаем номер квартиры
        $this->data['house'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.Premise.PremiseNumber');

        $this->data['house'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.Premise.PremiseNumber');
        $this->locality_type = strripos($this->city, 'деревн') === false
                                ? self::LOCALITY_TYPE_CITY 
                                : self::LOCALITY_TYPE_VILLAGE;

        $this->name = ArrayHelper::getValue($object, 'name', '');
        $this->type = ArrayHelper::getValue($metaData, 'kind', '');
        $this->description = ArrayHelper::getValue($object, 'description', '');

        $this->point = new Point(['latitude' => $point[1], 'longitude' => $point[0]]);
    }
}