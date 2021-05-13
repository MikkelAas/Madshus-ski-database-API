<?php
require('models/PlanModel.php');


$planModel = new PlanModel();

switch ($method){
  case "POST":
    if (array_key_exists(4, $pathParts) && $pathParts[4] == "planner") {
      $data = json_decode(file_get_contents('php://input'), true);

      if (!array_key_exists("start_date", $data) || !array_key_exists("planned_skis", $data)) {
        http_response_code(400);
        echo json_encode(array("error" => "invalid input, check your json!"));
        return;
      }

      $newDate = $data['start_date'];
      $plannedSkis = $data['planned_skis'];

      if ($newDate == "" || !is_array($plannedSkis) || sizeof($plannedSkis) == 0) {
        http_response_code(400);
        echo json_encode(array("error" => "Invalid data"));
        return;
      }

      try {
        $planModel->createPlan($newDate, $plannedSkis);
        http_response_code(200);
      } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(array("error" => "Invalid date"));
        return;
      } catch (\Exception $e ) {
        http_response_code(400);
        echo json_encode(array("error" => "Error while inserting in db. Check your json!"));
        return;
      }
    }
    break;
  default:
    http_response_code(405);
    echo json_encode(array("error"=>"invalid method " . $method));
    return;
}