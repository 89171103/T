<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
header("Access-Control-Allow-Methods: DELETE");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-type, Access-Control-Allow-Methods');


$request_method = $_SERVER["REQUEST_METHOD"];

include_once '../config/Database.php';
include_once '../models/Tasks.php';

$database = new Database();
$db = $database->connect();

$tasks = new Tasks($db);

if ($request_method == 'DELETE') {


    $input = (array)json_decode(file_get_contents('php://input'), TRUE);
    if (!isset($input["id"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array('message' => "Invalid input", 'error' => 1));
    } else {

        $d_id = $input["id"];
        $tasks->delete($d_id);
        echo json_encode(array('message' => "Task Deleted Successfully", 'error' => 0));
    }

}