<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name     = $_POST['name'];
$phone    = $_POST['phone'];
$email    = $_POST['email'];
$password = $_POST['password'];

// Basic server-side validation
if (!preg_match('/^\d{10}$/', $phone)) {
    echo "Phone number must be exactly 10 digits.";
    exit;
}

if (strlen($password) < 8) {
    echo "Password must be 8 characters or less.";
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert data safely
$stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "User registered successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
} else {
    http_response_code(405);
    echo "Method not allowed";
}

?>
