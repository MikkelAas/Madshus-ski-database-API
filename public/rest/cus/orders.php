<?php

require_once ('models/OrderModel.php');
require_once ('models/PlanModel.php');

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
        } else if (array_key_exists(5, $pathParts) && is_numeric($pathParts[5]) && array_key_exists(4, $pathParts) && $pathParts[4] == "split"){
            try {
                $orderModel->splitOrder((int)$pathParts[5]);
            } catch (Exception $e){
                http_response_code(400);
                echo json_encode(array("error" => "failed to split the order"));
                return;
            }
        }
        else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
        }
        break;
    case "POST":
        if (array_key_exists(4, $pathParts) && str_ends_with($pathParts[4], "create")) {

            $res = json_decode(file_get_contents('php://input'), true);

            if (!(
                array_key_exists('total_price', $res)
                && array_key_exists('reference_to_larger_order', $res)
                && array_key_exists('customer_id', $res)
                && array_key_exists('state', $res)
                && array_key_exists('employee_id', $res)
                && array_key_exists('skis', $res)
            )
            ){
                http_response_code(400);
                echo json_encode(array("error" => "invalid input, check your json!"));
                return;
            }

            $totalPrice = $res['total_price'];
            $refToLargerOrder = $res['reference_to_larger_order'];
            $customerId = $res['customer_id'];
            $stateMessage = $res['state'];
            $employeeId = $res['employee_id'];
            // TODO: Fix this. Doesn't align with json input.
            $skis = $res['skis'];
            $skiTypeQuantityMap = [];

            foreach ($skis as $ski){
                if (!(array_key_exists('ski_type_id', $ski) && array_key_exists('quantity', $ski))){
                    http_response_code(400);
                    echo json_encode(array("error" => "invalid input, check your json!"));
                    return;
                }
                $skiTypeQuantityMap[$ski['ski_type_id']] = $ski['quantity'];
            }

            $orderModel->createOrder(
                $totalPrice,
                $refToLargerOrder,
                $customerId,
                $stateMessage,
                $employeeId,
                $skiTypeQuantityMap
            );
            http_response_code(201);
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
        }
        break;
    case "DELETE":
        if (array_key_exists(4, $pathParts) && is_numeric($pathParts[4]) && array_key_exists(3, $pathParts) && $pathParts[3] == 'orders'){
            $res = $orderModel->getOrder($pathParts[4]);
            echo var_dump($res);
            $orderModel->deleteOrder($pathParts[4]);
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("error"=>"invalid method " . $method));
        return;
}


if (!empty($res)){
    if (sizeof($res) == 1){
        $res = $res[0];
    }
    echo json_encode($res);
} else {
    http_response_code(400);
    echo json_encode(array("error"=>"no results"));
}

