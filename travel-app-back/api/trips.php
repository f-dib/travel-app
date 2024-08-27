<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';  // Collegamento al database
include_once '../models/Trip.php';  // Collegamento al modello Trip

if ($db === null) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$trip = new Trip($db);

$data = json_decode(file_get_contents('php://input'), true);

// Logga il contenuto grezzo
error_log('Raw data received: ' . file_get_contents('php://input'));

// Logga il JSON decodificato
error_log('Decoded data: ' . print_r($data, true));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id']) && isset($_GET['day'])) {
            $result = $trip->getDayByTripId($_GET['id'], $_GET['day']);
        } elseif (isset($_GET['id'])) {
            $result = $trip->getById($_GET['id']);
        } else {
            $result = $trip->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        if (!isset($data['title'], $data['description'], $data['start_date'], $data['cover'])) {
            echo json_encode(['success' => false, 'message' => 'Campi mancanti']);
            exit;
        }
        $trip->title = $data['title'];
        $trip->description = $data['description'];
        $trip->start_date = $data['start_date'];
        $trip->cover = $data['cover'];
        if ($trip->create()) {
            echo json_encode(['success' => true, 'trip_id' => $trip->id]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $trip->id = $data['id'];
        $trip->title = $data['title'];
        $trip->description = $data['description'];
        $trip->start_date = $data['start_date'];
        $trip->cover = $data['cover'];
        if ($trip->update()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $trip->id = $_GET['id'];
            if ($trip->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Metodo non supportato.']);
        break;
}
