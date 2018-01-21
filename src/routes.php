<?php
/**
 * The routes of the api
 */
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("api home");

    return $response;
});

$app->get('/version', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("api version 0.1");

    return $response;
});