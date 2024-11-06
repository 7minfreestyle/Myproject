<?php
// Enable error reporting for debugging purposes (useful during development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set your database connection variables
$host = 'localhost';
$username = 'root';  // Default for XAMPP
$password = '';      // Default for XAMPP
$dbname = 'db';      

// Create a connection to the database using mysqli
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Display initial memory usage (optional)
echo "Memory before query: " . memory_get_usage() . " bytes<br>";

// Example of a safe SELECT query using prepared statements (avoiding raw query with user input)
$sql = "SELECT id, username FROM users LIMIT 10"; // Limit to 10 users to prevent large data fetch

// Prepare the SQL statement to avoid SQL injection (even though no dynamic input is used)
if ($stmt = $conn->prepare($sql)) {
    // Execute the query
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    // Check if any rows are returned
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($id, $username);

        // Fetch and display each row
        while ($stmt->fetch()) {
            echo "User ID: " . $id . " - Username: " . $username . "<br>";
        }
    } else {
        echo "No users found.<br>";
    }

    // Free the result set and close the statement
    $stmt->free_result();
    $stmt->close();
} else {
    echo "Query failed: " . $conn->error . "<br>";
}

// Close the database connection
$conn->close();

// Debugging: Final memory usage after closing the connection
echo "Memory after closing connection: " . memory_get_usage() . " bytes<br>";
?>
