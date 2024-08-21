<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");
// stops.php
include_once '../config/db.php';
include_once '../models/Stage.php';

$stop = new Stage($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['day_id'])) {
            $result = $stop->getByDayId($_GET['day_id']);
        } elseif (isset($_GET['id'])) {
            $result = $stop->getById($_GET['id']);
        } else {
            $result = $stop->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stop->day_id = $data['day_id'];
        $stop->title = $data['title'];
        $stop->description = $data['description'];
        $stop->location = $data['location'];
        $stop->image = $data['image'];
        if ($stop->create()) {
            echo json_encode(['success' => true, 'stop_id' => $stop->id]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stop->id = $data['id'];
        $stop->day_id = $data['day_id'];
        $stop->title = $data['title'];
        $stop->description = $data['description'];
        $stop->location = $data['location'];
        $stop->image = $data['image'];
        if ($stop->update()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stop->id = $_GET['id'];
            if ($stop->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
        break;
}
