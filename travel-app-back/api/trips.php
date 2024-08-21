<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");
include_once '../config/db.php';  // Collegamento al database
include_once '../models/Trip.php';  // Collegamento al modello Trip

$trip = new Trip($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $trip->getById($_GET['id']);
        } else {
            $result = $trip->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['title']) && !empty($data['description'])) {
            $trip->title = $data['title'];
            $trip->description = $data['description'];
            $trip->start_date = $data['start_date'];
            if ($trip->create()) {
                echo json_encode(['success' => true, 'trip_id' => $trip->id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante la creazione del viaggio.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dati mancanti per creare il viaggio.']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['id']) && !empty($data['title']) && !empty($data['description'])) {
            $trip->id = $data['id'];
            $trip->title = $data['title'];
            $trip->description = $data['description'];
            $trip->start_date = $data['start_date'];
            if ($trip->update()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento del viaggio.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dati mancanti per aggiornare il viaggio.']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $trip->id = $_GET['id'];
            if ($trip->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante la cancellazione del viaggio.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID non specificato.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Metodo non supportato.']);
        break;
}
