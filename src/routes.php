<?php
/**
 * The routes of the api
 */
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/', function (Request $request, Response $response, array $args) {
    $this->logger->info("travel-guide api '/' route");
    $response->getBody()->write("api home");

    return $response;
});

$app->get('/version', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/version' route");
    $response->getBody()->write("api version 0.1");

    return $response;
});


$app->get('/all', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    // Select all
    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id;
SQL;

    //$sql = 'SELECT * FROM `data`';

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});