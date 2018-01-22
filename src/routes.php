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

$app->get('/data/{id}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];
    $this->logger->debug("travel-guide api '/data/$id' route");
    
    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE id = :id;
SQL;

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/all', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    // Select all
    $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id;
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/all/full', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    // Select all
    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id;
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

// Sights
$app->get('/sights', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/sights' route");

    $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Αξιοθέατα';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/sights/full', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/sights' route");

    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Αξιοθέατα';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/beaches', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/beaches' route");

    $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Παραλίες';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/beaches/full', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/beaches' route");

    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Παραλίες';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/places', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/places' route");


    $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Οικισμός';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});

$app->get('/places/full', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/places' route");


    $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Οικισμός';
SQL;

    $stmt = $this->db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

});