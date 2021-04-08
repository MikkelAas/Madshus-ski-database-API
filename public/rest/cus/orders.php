<?php

require ('models/OrderModel.php');

$orderModel = new OrderModel();

switch ($method){
    case "GET":
        if (array_key_exists("since", $queries)){
            $res = $orderModel->getOrders($queries['since']);
        } else {
            $res = $orderModel->getOrders('');
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("error"=>"invalid method " . $method));
        return;
}
if (!empty($res)){
    echo json_encode($res);
} else {
    http_response_code(400);
    echo json_encode(array("error"=>"no results"));
}

