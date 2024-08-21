<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");
// days.php
include_once '../config/db.php';
include_once '../models/Day.php';

$day = new Day($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['trip_id'])) {
            $result = $day->getByTripId($_GET['trip_id']);
        } elseif (isset($_GET['id'])) {
            $result = $day->getById($_GET['id']);
        } else {
            $result = $day->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $day->trip_id = $data['trip_id'];
        $day->day_number = $data['day_number'];
        $day->date = $data['date'];
        if ($day->create()) {
            echo json_encode(['success' => true, 'day_id' => $day->id]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $day->id = $data['id'];
        $day->trip_id = $data['trip_id'];
        $day->day_number = $data['day_number'];
        $day->date = $data['date'];
        if ($day->update()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $day->id = $_GET['id'];
            if ($day->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
        break;
}
