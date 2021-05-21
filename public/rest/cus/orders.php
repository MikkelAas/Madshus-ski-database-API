<?php

require_once ('models/OrderModel.php');
require_once ('models/PlanModel.php');

$orderModel = new OrderModel();
$planModel = new PlanModel();


switch ($method){
    case "GET":
        if (array_key_exists(4, $pathParts) && is_numeric($pathParts[4])) {
            // Retrieves a specific order.
            $res = $orderModel->getOrder($pathParts[4]);
        } else if(array_key_exists("since", $queries)){
            // Retrieves all orders after a specific date.
            $res = $orderModel->getOrders($queries["since"]);
        } else if (!array_key_exists(4, $pathParts)){
            // If neither id nor date is specified, retrieve all orders.
            $res = $orderModel->getOrders(NULL);
        } else if (array_key_exists(3, $pathParts) && str_starts_with($pathParts[3], 'plan')) {
            // Retrieves the newest plan model.
            $res = $planModel->getPlan();
        } else if (array_key_exists(5, $pathParts) && is_numeric($pathParts[5]) && array_key_exists(4, $pathParts) && $pathParts[4] == "split"){
            // Tries to split an order.
            // This works in the PDO, but not here in the API.
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
        // Creates an order.
        if (array_key_exists(4, $pathParts) && str_ends_with($pathParts[4], "create")) {

            $res = json_decode(file_get_contents('php://input'), true);

            // Checks that the json body is correctly formatted.
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

            // Assigns value for each value parsed in the json body.
            $totalPrice = $res['total_price'];
            $refToLargerOrder = $res['reference_to_larger_order'];
            $customerId = $res['customer_id'];
            $stateMessage = $res['state'];
            $employeeId = $res['employee_id'];
            $skis = $res['skis'];
            $skiTypeQuantityMap = [];

            // Loops through and assigns all ski type ids and their quantities.
            foreach ($skis as $ski){
                if (!(array_key_exists('ski_type_id', $ski) && array_key_exists('quantity', $ski))){
                    http_response_code(400);
                    echo json_encode(array("error" => "invalid input, check your json!"));
                    return;
                }
                $skiTypeQuantityMap[$ski['ski_type_id']] = $ski['quantity'];
            }

            // Uses the PDO model to create the order.
            $orderModel->createOrder(
                $totalPrice,
                $refToLargerOrder,
                $customerId,
                $stateMessage,
                $employeeId,
                $skiTypeQuantityMap
            );
            // Returns response code 201 for created.
            http_response_code(201);
        } else {
            http_response_code(400);
            echo json_encode(array("error"=>"invalid endpoint"));
        }
        break;
    case "DELETE":
        // Deletes an entry in the database.
        if (array_key_exists(4, $pathParts) && is_numeric($pathParts[4]) && array_key_exists(3, $pathParts) && $pathParts[3] == 'orders'){
            $res = $orderModel->getOrder($pathParts[4]);
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
    // If only one element exists in the response array, remove the "array boundary".
    if (sizeof($res) == 1){
        $res = $res[0];
    }
    echo json_encode($res);
} else {
    http_response_code(400);
    echo json_encode(array("error"=>"no results"));
}

