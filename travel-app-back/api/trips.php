<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: multipart/form-data");

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
        // Verifica se il file 'cover' è presente e non ci sono errori
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover']['tmp_name'];
            $fileName = $_FILES['cover']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Imposta la directory di upload
            $upload_dir = __DIR__ . '/uploads/';
            $upload_file = $upload_dir . basename($fileName);

            // Verifica e crea la directory se non esiste
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Sposta il file nella directory di upload
            if (move_uploaded_file($fileTmpPath, $upload_file)) {
                // Salva solo il nome del file nel database
                $trip->cover = $fileName;  // Salva solo il nome del file
            } else {
                error_log('Failed to move uploaded file from ' . $fileTmpPath . ' to ' . $upload_file);
                echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
                exit;
            }
        } else {
            // Se il file non è presente o ci sono errori, imposta cover a null o gestisci l'errore
            $trip->cover = null;  // O imposta un percorso di default
        }

        // Verifica che gli altri campi siano presenti
        if (!isset($_POST['title'], $_POST['description'], $_POST['start_date'], $_POST['number_of_days'])) {
            echo json_encode(['success' => false, 'message' => 'Campi mancanti']);
            exit;
        }

        // Imposta le altre proprietà dell'oggetto Trip
        $trip->title = $_POST['title'];
        $trip->description = $_POST['description'];
        $trip->start_date = $_POST['start_date'];
        $trip->number_of_days = $_POST['number_of_days'];

        // Crea il trip e i giorni associati
        if ($trip->createWithDays()) {
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