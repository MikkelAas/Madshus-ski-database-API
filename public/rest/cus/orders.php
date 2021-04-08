<?php

require ('models/OrderModel.php');

$orderModel = new OrderModel();


switch ($method){
    case "GET":
        if (array_key_exists(4, $pathParts) && is_int($pathParts[4])) {
            $res = $orderModel->getOrder($pathParts[4]);
        } else if(array_key_exists("since", $queries)){
            $res = $orderModel->getOrders($queries["since"]);
        } else if (!array_key_exists(4, $pathParts)){
            $res = $orderModel->getOrders(NULL);
        }
        break;
    case "POST":
        if (array_key_exists(4, $pathParts) && $pathParts[4] == "create") {

            $data = json_decode(file_get_contents('php://input'), true);

            $totalPrice = $data['total_price'];
            $refToLargerOrder = $data['reference_to_larger_order'];
            $customerId = $data['customer_id'];
            $stateMessage = $data['state'];
            $employeeId = $data['employee_id'];
            $skiTypeId = $data['ski_type_id'];
            $quantity = $data['quantity'];

            $orderModel->createOrder($totalPrice, $refToLargerOrder, $customerId, $stateMessage, $employeeId, $skiTypeId, $quantity);
            return;
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
            return;
        }
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

