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

// Return the api version
$app->get('/version', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/version' route");
    $response->getBody()->write("api version 0.1");

    return $response;
});

// Return one data element full
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

})->add($pmw);

// Route /all - Return all data
$app->get('/all', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    $params = $request->getAttribute('params');

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id 
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);


// Return all sights
$app->get('/sights', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/sights' route");

    $params = $request->getAttribute('params');

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Αξιοθέατα'
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Αξιοθέατα' 
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);



// Return all beaches
$app->get('/beaches', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/beaches' route");

    $params = $request->getAttribute('params');

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Παραλίες'
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Παραλίες'
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);


// Return all places
$app->get('/places', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/places' route");

    $params = $request->getAttribute('params');

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT *
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Οικισμός'
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail
FROM data
LEFT JOIN counts ON data.id = counts.data_id
WHERE category = 'Οικισμός'
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;


})->add($pmw);
