<?php
$servername = "localhost";
$username = "homestead";
$password = "secret";
$dbname = "user_registration";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the user table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(255) NULL,
    created INT(11) NULL,
    updated INT(11) NULL
)";

// Execute the query
if ($conn->query($sql) === true) {
    echo "User table created successfully.";
} else {
    echo "Error creating user table: " . $conn->error;
}

// Close the connection
$conn->close();

