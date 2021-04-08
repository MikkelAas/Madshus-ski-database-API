<?php

const endpoints = array(
    "/rest/diag"=>"public/rest/endpoints/diag.php",
    "/rest/pub/skis"=>"public/rest/pub/skis.php"
);

require_once('../../config/config.php');

$queries = array();

$path = $_SERVER['REQUEST_URI'];
parse_str($_SERVER['QUERY_STRING'], $queries);
$method = $_SERVER["REQUEST_METHOD"];
$pathParts = explode("/", $path);


header("content-type: application/json");

$matchedEndpoint = false;

foreach (endpoints as $endpoint=>$endpoint_path) {
    // If path starts with endpoint
    if (strpos($path, $endpoint) === 0) {
        $matchedEndpoint = true;
        require($endpoint_path);
    }
}

if (!$matchedEndpoint) {
    http_response_code(404);
    echo json_encode(array("error"=>"That endpoint does not exist!"));
}