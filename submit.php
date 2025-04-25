<?php
// Database connection details
$host = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "registration_dbrb"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Could not connect to the database. Please try again later.");
}

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields and non-empty values
    if (
        !isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['gender'], $_POST['birthdate'], $_POST['country']) ||
        empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['gender']) || empty($_POST['birthdate']) || empty($_POST['country'])
    ) {
        die("Missing required fields");
    }

    // Validate if user agreed to terms
    if (!isset($_POST['terms'])) {
        die("You must agree to the terms and conditions");
    }

    // Collect and sanitize form data
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password
    $gender = htmlspecialchars($_POST['gender']);
    $birthdate = $_POST['birthdate']; // Dates don't need htmlspecialchars
    $country = htmlspecialchars($_POST['country']);
    $agreedToTerms = isset($_POST['terms']) ? 1 : 0;

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare SQL query
    $sql = "INSERT INTO users (first_name, last_name, email, contact, password, gender, birthdate, country, agreed_to_terms)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssi",
        $firstName,
        $lastName,
        $email,
        $contact,
        $password,
        $gender,
        $birthdate,
        $country,
        $agreedToTerms
    );

    // Execute query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
