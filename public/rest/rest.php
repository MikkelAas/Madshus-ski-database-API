<?php

require_once('../../config/config.php');
require_once('models/AccessToken.php');

$endpoints = [
    "/rest/diag"=>["public/rest/endpoints/diag.php", new Privileges(false, false, false)],
    "/rest/pub/skis"=>["public/rest/pub/skis.php", new Privileges(false, false, false)],
    "/rest/trans/shipments"=>["public/rest/trans/shipments.php", new Privileges(false, false, true)],
    "/rest/trans/orders"=>["public/rest/trans/shipments.php", new Privileges(false, false, true)],
    "/rest/trans/update"=>["public/rest/trans/shipments.php", new Privileges(false, false, true)],
    "/rest/com/prod/planner"=>["public/rest/com/prod/planner.php", new Privileges(true, false, false)],
    "/rest/com/stor/register"=>["public/rest/com/stor/keeper.php", new Privileges(true, false, false)],
    "/rest/com/stor/orders"=>["public/rest/com/stor/keeper.php", new Privileges(true, false, false)],
    "/rest/com/cus/orders"=>["public/rest/com/cus/customer.php", new Privileges(true, false, false)],
    "/rest/com/cus/req_ship"=>["public/rest/com/cus/customer.php", new Privileges(true, false, false)],
    "/rest/cus/orders"=>["public/rest/cus/orders.php", new Privileges(false, true, false)]
  ];

$queries = [];

$path = $_SERVER['REQUEST_URI'];
parse_str($_SERVER['QUERY_STRING'], $queries);
$method = $_SERVER["REQUEST_METHOD"];
$pathParts = explode("/", $path);

$token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : "";

header("content-type: application/json");

$matchedEndpoint = false;

$incomingBody = file_get_contents('php://input');
if ($incomingBody != "" && !isJson($incomingBody)) {
  http_response_code(404);
  die(json_encode(["error"=>"data is invalid. make sure json structure is valid!"]));
}

foreach ($endpoints as $endpoint=>[$endpoint_path, $privileges]) {
    // If path starts with endpoint
    if (strpos($path, $endpoint) === 0) {
        // Check if access token is required
        if (!$privileges->hasNoPrivileges() && empty($token)) {
            http_response_code(401);
            die(json_encode(["error"=>"Access token required. Add 'TOKEN' header to request."]));
        }

        // If empty token, set extended privileges to none
        // Otherwise, get privileges from provided token
        if (empty($token)) {
            $tokenPrivileges = new Privileges(false, false, false);
        } else {
            $tokenPrivileges = (new AccessToken())->getPrivileges($token);

            // Check if token provided is invalid
            if (!$tokenPrivileges) {
                http_response_code(404);
                die(json_encode(["error"=>"Access token provided does not exist"]));
            }
        }

        // Check if access token has been granted the right privileges
        if (!$tokenPrivileges->hasAccess($privileges)) {
            http_response_code(403);
            die(json_encode(["error"=>"Access token does not allow access to this endpoint"]));
        }

        $matchedEndpoint = true;
        require($endpoint_path);
    }
}

if (!$matchedEndpoint) {
    http_response_code(404);
    echo json_encode(["error"=>"That endpoint does not exist!"]);
}

function isJson(string $string): bool {
  json_decode($string);
  return json_last_error() === JSON_ERROR_NONE;
}