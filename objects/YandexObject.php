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
        $administrative = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea')
            ? ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea')
            : ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea');

        $locality = ArrayHelper::getValue($administrative, 'Locality');

        $thoroughfare = $this->processThoroughfare($locality);

        // Получаем полный адрес
        $adressLine = ArrayHelper::getValue($metaData, 'text');
        $this->address = $adressLine;
        // Получаем город
        $this->data['city'] = ArrayHelper::getValue($administrative, 'Locality.LocalityName');
        $this->city = $this->data['city'];
        // Получаем округ/область
        $this->data['area'] = ArrayHelper::getValue($metaData,
            'AddressDetails.Country.AdministrativeArea.AdministrativeAreaName');

        $this->data['sub_area'] = ArrayHelper::getValue($metaData,
            'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName');

        $this->data['dependent_locality'] = ArrayHelper::getValue($metaData,
            'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.DependentLocality.DependentLocalityName');

        // Страну
        $this->data['country'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryName');
        // Код страны
        $this->data['countrySlug'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryCode');
        // Получаем ветку/улицу
        $this->data['thoroughfare'] = $this->processStreetName($thoroughfare);
        $this->data['street'] = $this->data['thoroughfare'];
        // Получаем номер дома
        $this->data['house'] = ArrayHelper::getValue($thoroughfare, 'Premise.PremiseNumber');
        $this->locality_type = $this->defineLocalityType($adressLine);
        $this->name = ArrayHelper::getValue($object, 'name', '');
        $this->type = ArrayHelper::getValue($metaData, 'kind', '');
        $this->description = ArrayHelper::getValue($object, 'description', '');
        $this->point = new Point(['latitude' => $point[1], 'longitude' => $point[0]]);
    }

    public function processThoroughfare($locality)
    {
        $thoroughfare = null;

        if(!empty($locality['Premise'])) {
            return $locality;
        }

        if (!empty($locality['DependentLocality'])) {
            $thoroughfare = self::recursiveProcess($locality, 'DependentLocality');

            if(ArrayHelper::getValue($thoroughfare, 'Thoroughfare')) {
                return ArrayHelper::getValue($thoroughfare, 'Thoroughfare');
            }
        }

        if (!empty($locality['Thoroughfare'])) {
            $thoroughfare = self::recursiveProcess($locality, 'Thoroughfare');
        }

        return $thoroughfare;
    }

    public static function recursiveProcess($data, $name)
    {
        $value = ArrayHelper::getValue($data, $name);
        if($value === null) {
            return $data;
        }

        return self::recursiveProcess($value, $name);
    }

    public function processStreetName($thoroughfare)
    {
        $street = null;

        if(ArrayHelper::getValue($thoroughfare, 'ThoroughfareName')) {
            $street = ArrayHelper::getValue($thoroughfare, 'ThoroughfareName');
        }
        if(ArrayHelper::getValue($thoroughfare, 'DependentLocalityName')) {
            $street = ArrayHelper::getValue($thoroughfare, 'DependentLocalityName');
        }

        return $street;
    }

    public function defineLocalityType($address)
    {
        $objectType = null;

        $address = str_replace('ё', 'е', $address);

        foreach(['деревня', 'поселок', 'коттеджный поселок'] AS $type) {
            if(strripos($address, $type)) {
                switch($type) {
                    case "деревня":
                        $objectType = self::LOCALITY_TYPE_VILLAGE;
                        break;
                    case "поселок":
                        $objectType = self::LOCALITY_TYPE_SETTLEMENT;
                        break;
                    case "коттеджный поселок":
                        $objectType = self::LOCALITY_TYPE_COTTAGE_VILLAGE;
                        break;
                    default:
                        $objectType = self::LOCALITY_TYPE_CITY;
                        break;
                }
            }
        }

        return $objectType;
    }
}