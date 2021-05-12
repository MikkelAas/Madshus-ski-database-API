<?php
require('models/OrderModel.php');
require('models/ShipmentModel.php');

$shipmentModel = new ShipmentModel();
$orderModel = new OrderModel();

switch ($method) {
  case "GET":
    // echo var_dump($pathParts);
    if (array_key_exists(3, $pathParts) && $pathParts[3] == "orders") {
      $res = $orderModel->getOrdersBasedOnState("ready to be shipped");
    } else if (array_key_exists(3, $pathParts) && $pathParts[3] == "shipments") {
      $res = $shipmentModel->getAllShipments();
    } else {
      http_response_code(400);
      echo json_encode(array("error" => "invalid endpoint"));
      return;
    }
    break;
  case "PATCH":
    if (array_key_exists(4, $pathParts) && array_key_exists(3, $pathParts) && $pathParts[3] == "update") {
      $data = json_decode(file_get_contents('php://input'), true);

      $newState = $data['state'];
      $shipmentId = $pathParts[4];

      if ($newState != "" || $shipmentId != "") {
        if ($newState == 'not ready' || $newState == 'ready' || $newState =='picked up') {
          $updatedRows = $shipmentModel->updateShipment($shipmentId , $newState);

          if ($updatedRows == 1) {
            http_response_code(200);
            return;
          } else  {
            http_response_code(400);
            echo json_encode(array("error" => "Something went wrong updating shipment"));
            return;
          }
        } else {
          http_response_code(400);
          echo json_encode(array("error" => "invalid state!"));
          return;
        }
      } else {
        http_response_code(400);
        echo json_encode(array("error" => "state or id empty"));
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