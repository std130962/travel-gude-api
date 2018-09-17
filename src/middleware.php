<?php
/**
 * The middlaware file
 */

// Parameters middlware
$pmw = function ($request, $response, $next) {

    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

    // Get all parameters
    $parameters = $request->getQueryParams();
    $this->logger->debug("parameters: ", $parameters);


    $params = array();
    $params['limit'] = (int) helper::setLimit($parameters);
    $params['offset'] = (int) helper::setOffset($parameters);
    $params['full'] = (int) helper::setFull($parameters);

    $lngLat =  helper::setLatLng($parameters);

    if ( $lngLat) {
        $params['lng'] =  $lngLat[0];
        $params['lat'] =  $lngLat[1];
    }

    $params['order'] = helper::setOrder($parameters);

    $this->logger->debug("params: ", $params);

    $request = $request->withAttribute('params', $params);

    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');

    return $response;
};

// Access-Control-Allow-Origin
$hmw = function ($request, $response, $next) {

    //$response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');


    $response = $next($request, $response);

    return $response;

};