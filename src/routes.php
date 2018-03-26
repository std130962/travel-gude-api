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

// Return one iten full
$app->get('/items/{id}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];
    $this->logger->debug("travel-guide api '/items/$id' route");

    $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE id = :id;
SQL;

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results[0], null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);


$app->get('/all', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    $params = $request->getAttribute('params');

    $where = $params['where'];

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE $where
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id 
WHERE $where 
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $this->logger->debug($sql);

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
    $where = $params['where'];

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Αξιοθέατα' AND $where 
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Αξιοθέατα'  AND $where 
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //$results = array_map('utf8_encode', $results);
    $this->logger->debug($sql);
    $this->logger->debug("results",  $results );


    //$response = $response->withJson("test", null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);



// Return all beaches
$app->get('/beaches', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/beaches' route");

    $params = $request->getAttribute('params');
    $where = $params['where'];

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Παραλίες' AND $where
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Παραλίες' AND $where
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
    $where = $params['where'];

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Οικισμός' AND $where
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Οικισμός' AND $where
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

// Return all info
$app->get('/info', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/info' route");

    $params = $request->getAttribute('params');
    //$where = $params['where'];

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Γενικά' 
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Γενικά' 
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


$app->post('/register',  function (Request $request, Response $response, array $args) {
    $headerValueString = $request->getHeaderLine('Authorization');

    if ($headerValueString) {
        $guid = base64_decode(substr($headerValueString, 6));
    }

    $parsedBody = $request->getParsedBody();
    $guid = $parsedBody['guid'];
    $this->logger->debug("travel-guide api '/register' route " . $guid, $parsedBody);

    $sql = "SELECT guid FROM devices WHERE guid = :guid";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':guid', $guid, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        // The device exists in database
        $this->logger->debug("Check if guid exist ", $results);
    } else {
        // Put new device in database
        $sql = "INSERT INTO devices(guid,cordova,model,platform,manufacturer,isvirtual) VALUES(:guid,:cordova,:model,:platform,:manufacturer,:isvirtual)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parsedBody);

    }

    $data = 'ok';
    $response = $response->withJson($data, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($hmw);



// Return point
$app->get('/points', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/point' route");

   // $params = $request->getAttribute('params');


        $sql = <<<SQL
SELECT *
FROM testgeo
SQL;



    $stmt = $this->db->prepare($sql);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($results);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;


})->add($pmw);
