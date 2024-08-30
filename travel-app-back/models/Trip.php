<?php
class Trip {
    private $conn;
    private $table_name = "trips";

    public $id;
    public $title;
    public $description;
    public $start_date;
    public $cover;
    public $number_of_days;

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
        $query = "INSERT INTO " . $this->table_name . " (title, description, start_date, cover, number_of_days) VALUES (:title, :description, :start_date, :cover, :number_of_days)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        if (empty($this->start_date)) {
            $stmt->bindValue(':start_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':start_date', $this->start_date);
        }
        $stmt->bindParam(':cover', $this->cover);
        $stmt->bindParam(':number_of_days', $this->number_of_days);


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

    public function createWithDays()
    {
        // Insert the trip first
        if ($this->create()) {
            // Insert the days
            for ($i = 1; $i <= $this->number_of_days; $i++) {
                $current_date = date('Y-m-d', strtotime($this->start_date . ' + ' . ($i - 1) . ' days'));
                $query = "INSERT INTO days (trip_id, day_number, date) VALUES (:trip_id, :day_number, :date)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':trip_id', $this->id);
                $stmt->bindParam(':day_number', $i);
                $stmt->bindParam(':date', $current_date);
                if (!$stmt->execute()) {
                    error_log('Failed to insert day: ' . implode(' ', $stmt->errorInfo()));
                    return false;
                }
            }
            return true;
        } else {
            error_log('Failed to create trip: ' . implode(' ', $this->conn->errorInfo()));
            return false;
        }
    }

    public function update() {
        try {
            $this->conn->beginTransaction();
    
            $query = "UPDATE " . $this->table_name . " SET title = :title, description = :description, start_date = :start_date, cover = :cover, number_of_days = :number_of_days WHERE id = :id";
    
            $stmt = $this->conn->prepare($query);
    
            // Binding dei parametri
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':start_date', $this->start_date);
            $stmt->bindParam(':cover', $this->cover);
            $stmt->bindParam(':number_of_days', $this->number_of_days);
    
            if (!$stmt->execute()) {
                error_log('SQL Error: ' . implode(" ", $stmt->errorInfo()));
                $this->conn->rollBack();
                return false;
            }
    
            // Gestione dei giorni
            $query = "SELECT COUNT(*) as total_days FROM days WHERE trip_id = :trip_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':trip_id', $this->id);
            $stmt->execute();
            $current_days = $stmt->fetch(PDO::FETCH_ASSOC)['total_days'];
    
            if ($this->number_of_days > $current_days) {
                // Aggiungi giorni
                for ($i = $current_days + 1; $i <= $this->number_of_days; $i++) {
                    $date = date('Y-m-d', strtotime($this->start_date . ' + ' . ($i - 1) . ' days'));
                    $query = "INSERT INTO days (trip_id, day_number, date) VALUES (:trip_id, :day_number, :date)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':trip_id', $this->id);
                    $stmt->bindParam(':day_number', $i);
                    $stmt->bindParam(':date', $date);
                    if (!$stmt->execute()) {
                        error_log('Failed to add day: ' . implode(" ", $stmt->errorInfo()));
                        $this->conn->rollBack();
                        return false;
                    }
                }
            } elseif ($this->number_of_days < $current_days) {
                // Rimuovi giorni
                $query = "DELETE FROM days WHERE trip_id = :trip_id AND day_number > :day_number";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':trip_id', $this->id);
                $stmt->bindParam(':day_number', $this->number_of_days);
                if (!$stmt->execute()) {
                    error_log('Failed to delete excess days: ' . implode(" ", $stmt->errorInfo()));
                    $this->conn->rollBack();
                    return false;
                }
            }
    
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            $this->conn->rollBack();
            return false;
        }
    }

    public function delete() {
        // Elimina le tappe collegate
        $query = "DELETE FROM stages WHERE day_id IN (SELECT id FROM days WHERE trip_id = :trip_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $this->id);
        $stmt->execute();
    
        // Elimina i giorni collegati
        $query = "DELETE FROM days WHERE trip_id = :trip_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trip_id', $this->id);
        $stmt->execute();
    
        // Infine, elimina il trip
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
