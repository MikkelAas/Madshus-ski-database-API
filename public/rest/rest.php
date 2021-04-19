<?php

require_once('../../config/config.php');
require_once('models/AccessToken.php');

$endpoints = [
    "/rest/diag"=>["public/rest/endpoints/diag.php", new Privileges(false, false, false)],
    "/rest/pub/skis"=>["public/rest/pub/skis.php", new Privileges(false, false, false)]
];

$queries = [];

$path = $_SERVER['REQUEST_URI'];
parse_str($_SERVER['QUERY_STRING'], $queries);
$method = $_SERVER["REQUEST_METHOD"];
$pathParts = explode("/", $path);

$token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : "";

header("content-type: application/json");

$matchedEndpoint = false;

foreach ($endpoints as $endpoint=>[$endpoint_path, $privileges]) {
    // If path starts with endpoint
    if (strpos($path, $endpoint) === 0) {
        // Check if access token is required
        if (!$privileges->hasNoPrivileges() && empty($token)) {
            http_response_code(401);
            die(json_encode(["error"=>"Access token required. Add 'TOKEN' header to request."]));
        }

        $tokenPrivileges = (new AccessToken())->getPrivileges($token);

        // Check if access token exists
        if (!$tokenPrivileges) {
            http_response_code(401);
            die(json_encode(["error"=>"Access token is invalid"]));
        }

        // Check if access token has been granted the right privileges
        if (!$tokenPrivileges->hasPrivileges($privileges)) {
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