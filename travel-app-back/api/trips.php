<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: multipart/form-data");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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

error_log('Raw POST data: ' . print_r($_POST, true));
error_log('Files received: ' . print_r($_FILES, true));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id']) && isset($_GET['day']) && isset($_GET['stage'])) {
            $result = $trip->getStageByTripIdDayNumberAndStageNumber($_GET['id'], $_GET['day'], $_GET['stage']);
        } elseif (isset($_GET['id']) && isset($_GET['day'])) {
            $result = $trip->getDayByTripId($_GET['id'], $_GET['day']);
        } elseif (isset($_GET['id'])) {
            $result = $trip->getById($_GET['id']);
        } else {
            $result = $trip->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        // Verifica se l'ID è presente per distinguere tra creazione e modifica
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Modifica di un trip esistente
            $trip->id = $_POST['id'];
            $trip->title = $_POST['title'];
            $trip->description = $_POST['description'];
            $trip->start_date = $_POST['start_date'];
            $trip->number_of_days = $_POST['number_of_days'];

            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['cover']['tmp_name'];
                $fileName = $_FILES['cover']['name'];
                $upload_dir = __DIR__ . '/uploads/';
                $upload_file = $upload_dir . basename($fileName);

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $upload_file)) {
                    $trip->cover = $fileName;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
                    exit;
                }
            } else {
                $trip->cover = $_POST['cover'];  // Mantieni l'immagine esistente
            }

            if ($trip->update()) {
                echo json_encode(['success' => true, 'message' => 'Trip updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update trip']);
            }
        } else {
            // Creazione di un nuovo trip
            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['cover']['tmp_name'];
                $fileName = $_FILES['cover']['name'];
                $upload_dir = __DIR__ . '/uploads/';
                $upload_file = $upload_dir . basename($fileName);

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $upload_file)) {
                    $trip->cover = $fileName;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
                    exit;
                }
            } else {
                $trip->cover = null;  // Nessun file caricato
            }

            $trip->title = $_POST['title'];
            $trip->description = $_POST['description'];
            $trip->start_date = $_POST['start_date'];
            $trip->number_of_days = $_POST['number_of_days'];

            if ($trip->createWithDays()) {
                echo json_encode(['success' => true, 'trip_id' => $trip->id]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
        break;

    case 'PUT':
        // Gestione della richiesta PUT con $_POST e $_FILES
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover']['tmp_name'];
            $fileName = $_FILES['cover']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $upload_dir = __DIR__ . '/uploads/';
            $upload_file = $upload_dir . basename($fileName);

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $upload_file)) {
                $trip->cover = $fileName;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
                exit;
            }
        } else {
            $trip->cover = $_POST['cover'];
        }

        // Verifica e imposta gli altri campi
        $trip->id = $_POST['id'];
        $trip->title = $_POST['title'];
        $trip->description = $_POST['description'];
        $trip->start_date = $_POST['start_date'];
        $trip->number_of_days = $_POST['number_of_days'];

        if ($trip->update()) {
            echo json_encode(['success' => true, 'message' => 'Trip updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update trip']);
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