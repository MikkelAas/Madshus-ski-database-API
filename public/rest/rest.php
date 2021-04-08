<?php

const endpoints = array(
    "/rest/diag"=>"public/rest/endpoints/diag.php"
);

require_once('../../config/config.php');

$path = $_SERVER['REQUEST_URI'];

if (array_key_exists($path, endpoints)) {
    header("content-type: application/json");
    require(endpoints[$path]);
}