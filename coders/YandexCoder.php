<?php

namespace deka6pb\geocoder\coders;

use deka6pb\geocoder\abstraction\CoderAbstract;
use deka6pb\geocoder\objects\YandexObject;
use Curl\Curl;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class YandexCoder extends CoderAbstract
{
    protected static function execute($query, array $params = [])
    {
        $curl = new Curl();

        $data = $curl->get('https://geocode-maps.yandex.ru/1.x/', array_merge([
            'geocode' => $query, 'format' => 'json'
        ], $params));

        $limit = (int) ArrayHelper::getValue($params, 'results', 0);
        $objects = [];

        if (!$data) {
            return null;
        }
        
        try {
            $items = ArrayHelper::getValue($data, 'response.GeoObjectCollection.featureMember');

            if ($items) {
                foreach ($items as $item) {
                    $objects[] = new YandexObject(ArrayHelper::toArray($item));
                }
            }

            if ($limit === 1 && count($objects)) {
                return $objects[0];
            }

        } catch (\Exception $e) {
            return null;
        }

        return $objects;
    }

}