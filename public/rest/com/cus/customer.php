<?php

require("models/OrderModel.php");
require("models/ShipmentModel.php");

$orderModel = new OrderModel();
$shipmentModel = new ShipmentModel();

switch ($method) {
  case "GET":
    if (array_key_exists(4, $pathParts) && str_starts_with($pathParts[4], "orders")) {
      if (array_key_exists("state", $queries)) {
        $res = $orderModel->getOrdersBasedOnState($queries["state"]);
      } else {
        $res = $orderModel->getOrders(NULL);
      }
    } else {
      http_response_code(400);
      echo json_encode(array("error" => "invalid endpoint"));
      return;
    }
    break;

  case "POST":
    if (array_key_exists(4, $pathParts) && $pathParts[4] == "req_ship") {
      $data = json_decode(file_get_contents('php://input'), true);

      if (!array_key_exists("orders", $data) || !is_array($data["orders"]) || empty($data["orders"])) {
        http_response_code(400);
        echo json_encode(array("error" => "invalid input, check your json!"));
        return;
      }


      try {
        $shipmentModel->createShipmentRequest($data['orders']);
        http_response_code(200);
        return;
      } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array("error" => "Unable to create shipment request. Do all orders exist?"));
        return;
      }
    }

    break;
  case "PATCH":
    if (array_key_exists(4, $pathParts) && $pathParts[4] == "orders") {
      $data = json_decode(file_get_contents('php://input'), true);

      if (!array_key_exists("id", $data) || !array_key_exists("state", $data)) {
        http_response_code(400);
        echo json_encode(array("error" => "invalid input, check your json!"));
        return;
      }

      $orderId = $data['id'];
      $orderState = $data['state'];

      if ($orderId == "" || $orderState == "") {
        http_response_code(400);
        echo json_encode(array("error" => "Invalid data"));
        return;
      }

      try {
        $orderModel->changeOrderState($orderId, $orderState, NULL);
        http_response_code(200);
        return;
      } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(array("error" => "Invalid state"));
        return;
      } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array("error" => "Unable to update order"));
        return;
      }
    }

    break;
  default:
    http_response_code(405);
    echo json_encode(array("error"=>"invalid method " . $method));
    return;
}

if (!empty($res)) {
  echo json_encode($res);
} else {
  http_response_code(400);
  echo json_encode(array("error"=>"no results"));
}