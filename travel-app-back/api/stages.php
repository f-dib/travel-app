<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// stage.php
include_once '../config/db.php';
include_once '../models/Stage.php';

if ($db === null) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$stage = new Stage($db);

$data = json_decode(file_get_contents('php://input'), true);

// Logga il contenuto grezzo
error_log('Raw data received: ' . file_get_contents('php://input'));

// Logga il JSON decodificato
error_log('Decoded data: ' . print_r($data, true));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['day_id'])) {
            $result = $stage->getByDayId($_GET['day_id']);
        } elseif (isset($_GET['id'])) {
            $result = $stage->getById($_GET['id']);
        } else {
            $result = $stage->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        if (isset($data['day_id'], $data['stage_number'], $data['title'])) {
            $stage->day_id = $data['day_id'];
            $stage->stage_number = $data['stage_number'];
            $stage->title = $data['title'];
            $stage->description = $data['description'] ?? null;
            $stage->location = $data['location'] ?? null;
            $stage->latitude = $data['latitude'] ?? null;
            $stage->longitude = $data['longitude'] ?? null;
            $stage->image = $data['image'] ?? null;

            if ($stage->create()) {
                echo json_encode(['success' => true, 'stage_id' => $stage->id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante la creazione dello stage.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dati insufficienti per la creazione dello stage.']);
        }
        break;

    case 'PUT':
        if (isset($data['id'], $data['day_id'], $data['stage_number'], $data['title'])) {
            $stage->id = $data['id'];
            $stage->day_id = $data['day_id'];
            $stage->stage_number = $data['stage_number'];
            $stage->title = $data['title'];
            $stage->description = $data['description'] ?? null;
            $stage->location = $data['location'] ?? null;
            $stage->latitude = $data['latitude'] ?? null;
            $stage->longitude = $data['longitude'] ?? null;
            $stage->image = $data['image'] ?? null;

            if ($stage->update()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dello stage.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dati insufficienti per l\'aggiornamento dello stage.']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stage->id = $_GET['id'];
            if ($stage->delete()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dello stage.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID dello stage mancante per l\'eliminazione.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Metodo non supportato.']);
        break;
}
