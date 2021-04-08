<?php

const endpoints = array(
    "/rest/diag"=>"public/rest/endpoints/diag.php"
);

require_once('../../config/config.php');

$path = $_SERVER['REQUEST_URI'];

header("content-type: application/json");

if (array_key_exists($path, endpoints)) {
    require(endpoints[$path]);
} else {
    http_response_code(404);
    echo json_encode(array("error"=>"That endpoint does not exist!"));
}