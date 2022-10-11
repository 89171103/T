<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT");
header('Content-type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-type, Access-Control-Allow-Methods');

$request_method = $_SERVER["REQUEST_METHOD"];

include_once '../config/Database.php';
include_once '../models/Tasks.php';

$database = new Database();
$db = $database->connect();

$tasks = new Tasks($db);

if ($request_method == 'GET') {

    $task_id = isset($_GET['id']) ? $_GET['id'] : 0;

    $result = $tasks->get_task($task_id);
    $t = array();
    if ($result->rowCount() > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            extract($row); // $row['id'] -> $id

            $task = array(
                'id' => $id,
                'name' => $name,
                'date' => $endDate,
                'person' => $person,
                'desc' => $description
            );

            $t['data'][] = $task;

        }
        if (count($t['data']) < 2) {
            echo json_encode($t['data']);
        } else {
            echo json_encode($t);
        }

    } else {
        echo json_encode(array());
    }

}

if ($request_method == 'POST') {

    $input = (array)json_decode(file_get_contents('php://input'), TRUE);

    if (!isset($input["name"]) || !isset($input["person"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array('message' => "Invalid input"));
    } else {
        $data['name'] = $input["name"];
        $data['person'] = $input["person"];
        $data['desc'] = isset($input["description"]) ? $input["description"] : "";

        $tasks->create_task($data);
        echo json_encode(array('message' => "Inserting Success", 'error' => 0));
    }

}

if ($request_method == 'PUT') {

    $input = (array)json_decode(file_get_contents('php://input'), TRUE);

    if (isset($input['done']) && isset($input['id'])) {

        $data['done'] = $input['done'];
        $data['id'] = $input['id'];
        $tasks->end_task($data);
        echo json_encode(array('message' => "Success: Task Updated", 'error' => 0));

    } else {
        if (!isset($input["name"]) || !isset($input["person"]) || !isset($input['id'])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array('message' => "Invalid input", 'error' => 1));
        } else {
            $data['name'] = $input["name"];
            $data['person'] = $input["person"];
            $data['id'] = $input["id"];
            $data['desc'] = isset($input["description"]) ? $input["description"] : "";
            $tasks->update_task($data);
            echo json_encode(array('message' => "Success: Task Updated", 'error' => 0));
        }
    }
}