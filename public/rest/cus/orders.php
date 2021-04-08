<?php

require ('../../../config/config.php');
require ('models/OrderModel.php');

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

header("Content-Type: application/json; charset=UTF-8");

$orderModel = new OrderModel();
if (array_key_exists("since", $queries)){
    $res = $orderModel->getOrders($queries['since']);
} else {
    $res = $orderModel->getOrders('');
}

if (!empty($res)){
    echo json_encode($res);
}

