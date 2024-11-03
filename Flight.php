<?php
class Flight {
    private $conn;

    // Constructor to receive the database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Function to search for flights based on departure and arrival cities
    public function searchFlights($from, $to) {
        $sql = "SELECT f.id, a.airline_name, f.departure_city, f.arrival_city, 
                       f.departure_time, f.seats, f.eco_seats, f.business_seats, 
                       f.eco_price, f.business_price, a.booked_seats
                FROM flights f 
                JOIN airlines a ON f.airline_id = a.id 
                WHERE f.departure_city = ? AND f.arrival_city = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $from, $to);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
}

?>
