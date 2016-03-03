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
        $area = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea')
                    ? ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.SubAdministrativeArea')
                    : ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea');

        $locality = ArrayHelper::getValue($area, 'Locality');

        $thoroughfare = $this->processThoroughfare($locality);

        // Получаем полный адрес
        $this->address = ArrayHelper::getValue($metaData, 'text');
        // Получаем город
        $this->data['city'] = ArrayHelper::getValue($area, 'Locality.LocalityName');
        $this->city = $this->data['city'];
        // Получаем округ/область
        $this->data['area'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.AdministrativeArea.AdministrativeAreaName');
        // Страну
        $this->data['country'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryName');
        // Код страны
        $this->data['countrySlug'] = ArrayHelper::getValue($metaData, 'AddressDetails.Country.CountryCode');
        // Получаем ветку/улицу
        $this->data['thoroughfare'] = ArrayHelper::getValue($thoroughfare, 'ThoroughfareName');
        $this->data['street'] = $this->data['thoroughfare'];
        // Получаем номер дома
        $this->data['house'] = ArrayHelper::getValue($thoroughfare, 'Premise.PremiseNumber');
        $this->locality_type = strripos($this->city, 'деревн') === false
            ? self::LOCALITY_TYPE_CITY
            : self::LOCALITY_TYPE_VILLAGE;
        $this->name = ArrayHelper::getValue($object, 'name', '');
        $this->type = ArrayHelper::getValue($metaData, 'kind', '');
        $this->description = ArrayHelper::getValue($object, 'description', '');
        $this->point = new Point(['latitude' => $point[1], 'longitude' => $point[0]]);
    }

    public function processThoroughfare($locality)
    {
        while(!empty($locality['DependentLocality'])) {
            $locality = $locality['DependentLocality'];
        }


        if(!empty($locality['Thoroughfare'])) {
            return $locality['Thoroughfare'];
        }

        return null;
    }
}
