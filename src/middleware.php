<?php
/**
 * The middlaware file
 */

// Parameters middlware
$pmw = function ($request, $response, $next) {

    // Get all parameters
    $parameters = $request->getQueryParams();
    $this->logger->debug("parameters: ", $parameters);

    $params = array();
    $params['limit'] = (int) helper::setLimit($parameters);
    $params['offset'] = (int) helper::setOffset($parameters);
    $params['full'] = (int) helper::setFull($parameters);

    $this->logger->debug("params: ", $params);

    $request = $request->withAttribute('params', $params);

    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');

    return $response;
};