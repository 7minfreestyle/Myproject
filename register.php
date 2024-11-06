<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include('db.php');  // This will include the database connection from db.php

// Start the session to track login state (useful for logged-in users)
session_start();

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Show the data being submitted
    echo "Form submitted!<br>";

    // Get form data
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];  // Plaintext password entered by the user

        // Check if both fields are filled
        if (empty($username) || empty($password)) {
            echo "Both username and password are required.<br>";
            exit();
        }

        // Hash the password before inserting it into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Debugging: Show the hashed password
        echo "Hashed Password: $hashedPassword<br>";

        // Prepare SQL query to check if the username already exists
        if ($stmt = $conn->prepare("SELECT id FROM users WHERE username = ?")) {
            // Bind parameters
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();

            // Store the result
            $stmt->store_result();

            // If a user already exists with the same username
            if ($stmt->num_rows > 0) {
                echo "Username already exists! Please choose a different username.<br>";
            } else {
                // If no user exists with the same username, insert the new user
                if ($stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)")) {
                    // Bind parameters: (s for string)
                    $stmt_insert->bind_param("ss", $username, $hashedPassword);

                    // Execute the insert query
                    if ($stmt_insert->execute()) {
                        echo "User registered successfully!<br>";

                        // Optionally, set session variables and redirect to the login page or dashboard
                        $_SESSION['username'] = $username;

                        // Redirect to login page (or dashboard)
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Error: Could not execute the insert query. " . $stmt_insert->error . "<br>";
                    }

                    // Close the insert statement
                    $stmt_insert->close();
                }
            }

            // Close the select statement
            $stmt->close();
        }
    }
} else {
    echo "Form was not submitted correctly.<br>";
}

// Close the database connection (Only once at the end of the script)
$conn->close();
?>
