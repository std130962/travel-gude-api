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

// Return one item with full description
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


$app->get('/items', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    $params = $request->getAttribute('params');

    $order = $params['order'];
    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, likes, X(coords) AS lng, Y(coords) AS lat,
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
ORDER BY $order
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id 
ORDER BY $order
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $this->logger->debug($sql);
    $this->logger->debug($params['order']);

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    //$stmt->bindValue(':order', $params['order'], PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);


// todo remove this
$app->get('/nearby[/{items}]', function (Request $request, Response $response, array $args) {

    $this->logger->debug("travel-guide api '/nearby/{items}' route");

    $params = $request->getAttribute('params');

    $whereAnd = "";

    switch ($args['items']) {
        case "places":
            $whereAnd = " category = 'Οικισμός'";
            break;
        case "sights":
            $whereAnd = " category = 'Αξιοθέατα'";
            break;
        case "beaches":
            $whereAnd = " category = 'Παραλίες'";
            break;
        default:
            $whereAnd = " 1 ";
    }



    if (!isset($params['lat']) || !isset($params['lng']) ) {
        $params['lat'] = $this->center['lat'];
        $params['lng'] = $this->center['lng'];
        $this->logger->debug("from settings " . $params['lat'] . "-" .  $params['lng']);
    }

    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';
    $this->logger->debug("thePoint " . $thePoint);
    if ($params['full']) {
        $this->logger->debug("full ");
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id 
WHERE coords is not null
AND $whereAnd 
ORDER BY distance        
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $this->logger->debug("not full ");
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes,  X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id 
WHERE coords is not null
AND $whereAnd 
ORDER BY distance
LIMIT :limit 
OFFSET :offset;
SQL;
    }

    $this->logger->debug($sql);
    $this->logger->debug($params['order']);

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    //$stmt->bindValue(':order', $params['order'], PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $response->withJson($results, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($pmw);


// Return all sights
$app->get('/sights', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/sights' route");

    $params = $request->getAttribute('params');

    $order = $params['order'];
    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat,
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Αξιοθέατα'
ORDER BY $order 
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Αξιοθέατα' 
ORDER BY $order 
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

    $order = $params['order'];
    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Παραλίες'
ORDER BY $order 
LIMIT :limit 
OFFSET :offset;
SQL;
    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Παραλίες' 
ORDER BY $order 
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

// Return all places, sights, beaches
$app->get('/all', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/all' route");

    $params = $request->getAttribute('params');

    $order = $params['order'];
    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE X(coords) IS NOT NULL 
ORDER BY $order 
LIMIT :limit 
OFFSET :offset;
SQL;
    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE X(coords) IS NOT NULL 
ORDER BY $order 
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

//todo combinate categories
// Return all places
$app->get('/places', function (Request $request, Response $response, array $args) {
    $this->logger->debug("travel-guide api '/places' route");

    $params = $request->getAttribute('params');

    $order = $params['order'];
    $thePoint = 'POINT(' . $params['lng'] . ' ' . $params['lat'] . ')';

    if ($params['full']) {
        // show details
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, gallery, content, X(coords) AS lng, Y(coords) AS lat,
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Οικισμός' 
ORDER BY $order 
LIMIT :limit 
OFFSET :offset;
SQL;

    } else {
        $sql = <<<SQL
SELECT id, title, category, intro, image, thumbnail, likes, X(coords) AS lng, Y(coords) AS lat, 
round(ST_Distance_Sphere( `coords`, ST_GeomFromText('$thePoint'))) as distance
FROM items
LEFT JOIN counts ON items.id = counts.item_id
WHERE category = 'Οικισμός' 
ORDER BY $order 
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

$app->put('/favorites/{action}/{id}', function ($request, $response, $args) {

    $id = $args['id'];
    $action = $args['action'];

    $this->logger->debug("Favorites route: " . $action . " - " . $id);

    // Check if id is in counts table
    $isInCounts = false;
    $sql = "SELECT * FROM counts WHERE item_id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        // The id exists in table
        $this->logger->debug("The id exists in counts ", $results);
        $isInCounts = true;
    }

    if ($isInCounts) {
        if ($action == 'increase') {
            $results[0]['likes'] = $results[0]['likes'] + 1;
        } else if ($action == 'decrease') {
            if ($results[0]['likes'] > 0) {
                $results[0]['likes'] = $results[0]['likes'] - 1;
            } else {
                $results[0]['likes'] = 0;
            }
        } else {
            // Wrong action
        }
        // Update ...
        $sql = "UPDATE `counts` SET `likes` = :likes WHERE `item_id` = :id; ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':likes', $results[0]['likes'], PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Not in counts table. Insert it
        $sql = " INSERT INTO counts (item_id, views, likes) VALUES(:id, 0, 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        //$stmt->bindValue(':likes', $results[0]['likes'], PDO::PARAM_INT);
        $stmt->execute();
    }
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



$app->post('/history',  function (Request $request, Response $response, array $args) {

    $parsedBody = $request->getParsedBody();
    $this->logger->debug("travel-guide api '/history' route ", $parsedBody);

    // Check values
    if ($parsedBody['guid'] && $parsedBody['lat'] && $parsedBody['lng'] && $parsedBody['timestamp']) {

        $point = sprintf("POINT(%F %F)", $parsedBody['lng'], $parsedBody['lat']);
        $timestamp = date("Y-m-d H:i:s", $parsedBody['timestamp']);

        $sql = "INSERT INTO `history` (`guid`, `coords`, `timestamp`) VALUES (:guid, GeomFromText(:point), :timestamp);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':guid', $parsedBody['guid'], PDO::PARAM_STR);
        $stmt->bindValue(':point', $point, PDO::PARAM_STR);
        $stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->execute();

        $output = 'ok';

    } else {
        // No all values given. Return 404
        $output = 'not ok';
    }

    $response = $response->withJson($output, null, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    return $response;

})->add($hmw);





