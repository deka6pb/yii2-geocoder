<?php

namespace deka6pb\geocoder\coders;

use deka6pb\geocoder\abstraction\CoderAbstract;
use deka6pb\geocoder\objects\GoogleObject;
use Curl\Curl;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class GoogleCoder extends CoderAbstract
{
    protected static function execute($query, array $params = [])
    {
        $curl = new Curl();

        $data = $curl->get('https://maps.googleapis.com/maps/api/geocode/json', array_merge([
            'address' => $query
        ], $params));

        $limit = (int) ArrayHelper::getValue($params, 'results', 0);
        $objects = [];

        if (!$data) {
            return null;
        }

        try {
            $items = ArrayHelper::getValue($data, 'results');

            if ($items) {
                foreach ($items as $item) {
                    $objects[] = new GoogleObject(ArrayHelper::toArray($item));
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