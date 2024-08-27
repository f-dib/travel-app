<?php
class Trip {
    private $conn;
    private $table_name = "trips";

    public $id;
    public $title;
    public $description;
    public $start_date;
    public $cover;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Aggiungi i giorni e le tappe a ciascun viaggio
        foreach ($trips as &$trip) {
            $trip['days'] = $this->getDaysAndStages($trip['id']);
        }
        return $trips;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($trip) {
            $trip['days'] = $this->getDaysAndStages($trip['id']);
        }

        return $trip;
    }

    private function getDaysAndStages($trip_id) {
        // Recupera i giorni collegati a questo viaggio
        $query = "SELECT * FROM days WHERE trip_id = :trip_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->execute();
        $days = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Per ogni giorno, recupera le tappe collegate
        foreach ($days as &$day) {
            $day['stages'] = $this->getStages($day['id']);
        }

        return $days;
    }

    public function getDayByTripId($trip_id, $day_number)
    {
        $query = "SELECT * FROM days WHERE trip_id = :trip_id AND day_number = :day_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->bindParam(':day_number', $day_number);
        $stmt->execute();
        $day = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($day) {
            $day['stages'] = $this->getStages($day['id']);
        }

        return $day;
    }

    private function getStages($day_id) {
        $query = "SELECT * FROM stages WHERE day_id = :day_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':day_id', $day_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStageByTripIdDayNumberAndStageNumber($trip_id, $day_number, $stage_number) {
        // Fetch the specific day first
        $query = "SELECT id FROM days WHERE trip_id = :trip_id AND day_number = :day_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $trip_id);
        $stmt->bindParam(':day_number', $day_number);
        $stmt->execute();
        $day = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$day) {
            return null;  // Return null if the day is not found
        }
    
        // Fetch the specific stage based on the day_id and stage_number
        $query = "SELECT * FROM stages WHERE day_id = :day_id AND stage_number = :stage_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':day_id', $day['id']);
        $stmt->bindParam(':stage_number', $stage_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (title, description, start_date, cover) VALUES (:title, :description, :start_date, :cover)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        if (empty($this->start_date)) {
            $stmt->bindValue(':start_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':start_date', $this->start_date);
        }
        $stmt->bindParam(':cover', $this->cover);

        try {
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            } else {
                // Log dell'errore SQL se l'inserimento fallisce
                error_log('SQL Error: ' . implode(" ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            // Log dell'eccezione se si verifica un errore durante l'esecuzione
            error_log('PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':start_date', $this->start_date);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
