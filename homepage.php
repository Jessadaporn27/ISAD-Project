<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking Homepage</title>
    <link rel="stylesheet" href="homepage.css"> <!-- เชื่อมโยงกับไฟล์ CSS -->
</head>
<body>
    <div class="header">
        
    </div>
    <header>
        <h1>Welcome to Flight Booking</h1>
        <nav>
            <ul>
            <ul>
    <li><a href="homepage.php">Home</a></li>
    <li><a href="about.html">About Us</a></li>
    <li><a href="contact.html">Contact</a></li>
    <li>
        <?php
        session_start(); // เริ่มต้น session
        if (isset($_SESSION['username'])) {
            echo "User ID: " . htmlspecialchars($_SESSION['username']);
            echo ' <a href="logout.php" class="btn">Logout</a>'; // ปุ่ม Logout
        } else {
            echo '<a href="login.php" class="btn">Login</a>'; // ปุ่ม Login
        }
        ?>
    </li>
</ul>

            </ul>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <h2>Book Your Flight</h2>
            <form action="search.php" method="GET" id="booking-form">
                <div class="input-group">
                    <label for="from">From:</label>
                    <input type="text" id="from" name="from" placeholder="Departure City" required>
                </div>
                <div class="input-group">
                    <label for="to">To:</label>
                    <input type="text" id="to" name="to" placeholder="Destination City" required>
                </div>
                <div class="input-group">
                    <label for="departure-date">Departure Date:</label>
                    <input type="date" id="departure-date" name="departure-date" required>
                </div>
                <div class="input-group">
                    <label for="return-date">Return Date:</label>
                    <input type="date" id="return-date" name="return-date">
                </div>
                <button type="submit" class="btn">Search Flights</button>
            </form>
        </section>

        <section class="featured-flights">
            <h2>Featured Flights</h2>
            <div class="flight-list">
                <div class="flight-card">
                    <h3>Flight to New York</h3>
                    <p>Departure: 10:00 AM</p>
                    <p>Arrival: 1:00 PM</p>
                    <p>Price: $200</p>
                    <button class="btn">Book Now</button>
                </div>
                <div class="flight-card">
                    <h3>Flight to London</h3>
                    <p>Departure: 5:00 PM</p>
                    <p>Arrival: 8:00 PM</p>
                    <p>Price: $300</p>
                    <button class="btn">Book Now</button>
                </div>
                <div class="flight-card">
                    <h3>Flight to Tokyo</h3>
                    <p>Departure: 2:00 AM</p>
                    <p>Arrival: 6:00 AM</p>
                    <p>Price: $500</p>
                    <button class="btn">Book Now</button>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Flight Booking. All rights reserved.</p>
    </footer>

</body>
</html>
