<?php
require('models/SkiModel.php');


$skiModel = new SkiModel();

switch ($method){
    case "GET":
        if (array_key_exists(4, $pathParts)) {
            $res = $skiModel->getSkiTypeById($pathParts[4]);
        } else if (array_key_exists("model", $queries)) {
            $res = $skiModel->getSkiTypeByModel($queries["model"]);
        } else {
            $res = $skiModel->getAllSkiTypes();
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
