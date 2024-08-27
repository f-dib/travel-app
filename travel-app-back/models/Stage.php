<?php
class Stage {
    private $conn;
    private $table_name = "stages";

    public $id;
    public $day_id;
    public $stage_number;
    public $title;
    public $description;
    public $location;
    public $latitude;
    public $longitude;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByDayId($day_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE day_id = :day_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':day_id', $day_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (day_id, title, description, location, latitude, longitude, image) VALUES (:day_id, :title, :description, :location, :latitude, :longitude, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':day_id', $this->day_id);
        $stmt->bindParam(':stage_number', $this->stage_number);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':latitude', $this->latitude);
        $stmt->bindParam(':longitude', $this->longitude);
        $stmt->bindParam(':image', $this->image);
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET day_id = :day_id, title = :title, description = :description, location = :location, latitude = :latitude, longitude = :longitude, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':day_id', $this->day_id);
        $stmt->bindParam(':stage_number', $this->stage_number);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':latitude', $this->latitude);
        $stmt->bindParam(':longitude', $this->longitude);
        $stmt->bindParam(':image', $this->image);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
