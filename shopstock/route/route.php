<?php
require "../../settings/config.php";
require "./app/init.php";

$url = Route::parseURL();
$method = @$url[0];

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$function = new APIController();
if (method_exists($function, $method)) {
    $result = $function->$method($data);
} else {
    $result['error'] = "Method not available";
}

echo json_encode($result);
