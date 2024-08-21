<?php
class Trip {
    private $conn;
    private $table_name = "trips";

    public $id;
    public $title;
    public $description;
    public $start_date;

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

    private function getStages($day_id) {
        $query = "SELECT * FROM stages WHERE day_id = :day_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':day_id', $day_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, description) VALUES (:title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':start_date', $this->start_date);
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
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
