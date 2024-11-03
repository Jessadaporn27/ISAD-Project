<?php
class FlightSearch {
    private $conn;

    // รับการเชื่อมต่อฐานข้อมูลผ่าน constructor
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ดึงข้อมูลเมืองต้นทาง
    public function getDepartureCities() {
        $departureCities = [];
        $sql = "SELECT DISTINCT departure_city FROM airlines";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departureCities[] = $row['departure_city'];
            }
        }
        return $departureCities;
    }

    // ดึงข้อมูลเมืองปลายทาง
    public function getArrivalCities() {
        $arrivalCities = [];
        $sql = "SELECT DISTINCT arrival_city FROM airlines";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $arrivalCities[] = $row['arrival_city'];
            }
        }
        return $arrivalCities;
    }
}
?>
