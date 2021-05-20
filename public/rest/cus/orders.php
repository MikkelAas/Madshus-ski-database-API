<?php

require ('models/OrderModel.php');
require ('models/PlanModel.php');

$orderModel = new OrderModel();
$planModel = new PlanModel();


switch ($method){
    case "GET":
        if (array_key_exists(4, $pathParts) && is_numeric($pathParts[4])) {
            $res = $orderModel->getOrder($pathParts[4]);
        } else if(array_key_exists("since", $queries)){
            $res = $orderModel->getOrders($queries["since"]);
        } else if (!array_key_exists(4, $pathParts)){
            $res = $orderModel->getOrders(NULL);
        } else if (array_key_exists(3, $pathParts) && str_starts_with($pathParts[3], 'plan')) {
            $planModel->getPlan();
        }
        else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
        }
        break;
    case "POST":
        if (array_key_exists(4, $pathParts) && str_ends_with($pathParts[4], "create")) {

            $data = json_decode(file_get_contents('php://input'), true);

            $totalPrice = $data['total_price'];
            $refToLargerOrder = $data['reference_to_larger_order'];
            $customerId = $data['customer_id'];
            $stateMessage = $data['state'];
            $employeeId = $data['employee_id'];
            $skiTypeQuantityMap = $data['ski_type_quantity_map'];

            $orderModel->createOrder(
                $totalPrice,
                $refToLargerOrder,
                $customerId,
                $stateMessage,
                $employeeId,
                $skiTypeQuantityMap
            );
            http_response_code(201);
            return;
        } else if (array_key_exists(3, $pathParts[3]) && str_starts_with($pathParts[3], "split")){
            if (array_key_exists('order_id', $queries)){
                $orderModel->splitOrder($queries['order_id']);
            }
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
            return;
        }
    case "DELETE":
        if (array_key_exists(4, $pathParts) && is_numeric($pathParts[4]) && array_key_exists(3, $pathParts) && $pathParts[3] == 'orders'){
            $orderModel->deleteOrder($pathParts[4]);
            return;
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
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

