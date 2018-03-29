<?php

/**
 * Helper class
 *
 */
class Helper
{
    public static function setLimit($parameters)
    {
        if (isset($parameters['limit']) && is_numeric($parameters['limit'])) {
            return $parameters['limit'];
        } else {
            // return max 500 elements
            return 500;
        }
    }

    public static function setOffset($parameters)
    {
        if (isset($parameters['offset']) && is_numeric($parameters['offset'])) {
            return $parameters['offset'];
        } else {
            // if no offset return 0
            return 0;
        }
    }

    //Check if full=true then show all data
    public static function setFull($parameters)
    {
        if (isset($parameters['full']) && ($parameters['full'] == 1 || $parameters['full'] == "true")) {
            return 1; //true
        } else {
            return 0; //false
        }
    }

    //Check if distance is given (also need lat, lng coords)
    public static function setDistance($parameters)
    {
        if (isset($parameters['distance']) && is_numeric($parameters['distance'])
            && isset($parameters['lng']) && isset($parameters['lat'])
            && is_numeric($parameters['lng']) && is_numeric($parameters['lat'])
        ) {
            return $parameters['distance'];
        } else {
            return -1;
        }
    }

    public static function createSql($parameters)
    {
        if (isset($parameters['distance']) && is_numeric($parameters['distance'])
            && isset($parameters['lng']) && isset($parameters['lat'])
            && is_numeric($parameters['lng']) && is_numeric($parameters['lat'])
        ) {
            return $parameters['distance'];
        } else {
            return -1;
        }
    }

    //todo
    public static function fixUrls($results, $imagesUrl, $thumsUrl = 0)
    {
        if (!$thumsUrl) {
            $thumsUrl = $imagesUrl . 'thumbs/';
        }

    }


}