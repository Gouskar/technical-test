<?php 
// Database connection settings
$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "barbershop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Booking form submission logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $service = htmlspecialchars($_POST['service']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);

    // Prepare an SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, service, date, time) VALUES (?, ?, ?, ?, ?)");

    // Check if the preparation was successful
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters to the SQL statement
    $stmt->bind_param("sssss", $name, $email, $service, $date, $time);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $confirmationMessage = "<h2>Booking Confirmation</h2>
                                <p><strong>Name:</strong> $name</p>
                                <p><strong>Email:</strong> $email</p>
                                <p><strong>Preferred Service:</strong> $service</p>
                                <p><strong>Preferred Date:</strong> $date</p>
                                <p><strong>Preferred Time:</strong> $time</p>
                                <p>Your booking has been confirmed! Thank you for choosing our services.</p>
                                <button onclick='goBackToHome()' style='padding: 12px 25px; background-color: #ffd416; color: #333; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em;'>Go Back to Home</button>";
    } else {
        $confirmationMessage = "<p>Error: " . $stmt->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Book professional grooming services at The Barbershop.">
    <title>Barbershop Booking Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header" style="background-image: url('images/barbershop_bg.jpg'); background-size: cover; background-position: center; color: white;">
        <nav class="navbar" role="navigation">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#prices">Prices</a></li>
                <li><a href="#booking">Book Now</a></li>
            </ul>
        </nav>
        <div class="header-content" id="home">
            <h1>Welcome to The Barbershop</h1>
            <p>Professional grooming services for men</p>
        </div>
    </header>

    <section id="booking" class="booking-section">
        <h2>Book an Appointment</h2>

        <!-- Display confirmation message if the form was successfully submitted -->
        <?php if (isset($confirmationMessage)) echo $confirmationMessage; ?>

        <form action="booking.php" method="POST" class="booking-form">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <label for="service">Service</label>
            <select id="service" name="service" required>
                <option value="Haircut">Haircut</option>
                <option value="Shaving">Shaving</option>
                <option value="Beard Trim">Beard Trim</option>
            </select>
            
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
            
            <label for="time">Time</label>
            <input type="time" id="time" name="time" required>
            
            <button type="submit">Submit</button>
        </form>
    </section>
    
    <footer class="footer">
        <p>&copy; 2024 The Barbershop. All rights reserved.</p>
    </footer>

    <script>
        // Inline JavaScript to handle redirection after booking confirmation
        function goBackToHome() {
            window.location.href = "index.html";
        }
    </script>
</body>
</html>
