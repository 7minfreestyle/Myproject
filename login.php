<?php
// Enable error reporting
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

    // Get login credentials from the form
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];  // Plaintext password entered by the user

        // Debugging: Show the username and password from POST
        echo "Username: $username<br>";
        echo "Password: $password<br>";

        // Check if both fields are filled
        if (empty($username) || empty($password)) {
            echo "Both username and password are required.<br>";
            exit();
        }

        // Prepare SQL query to check for user existence
        if ($stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?")) {
            // Bind parameters
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();

            // Store the result
            $stmt->store_result();

            // Check if any user is found with the provided username
            if ($stmt->num_rows > 0) {
                // Bind the result
                $stmt->bind_result($user_id, $stored_username, $stored_password);

                // Fetch the result
                $stmt->fetch();

                // Verify the entered password with the stored hashed password
                if (password_verify($password, $stored_password)) {
                    // Password matches, user can log in
                    echo "Login successful! Welcome " . $stored_username;
                    
                    // Set session or redirect to the dashboard
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $stored_username;
                    
                    // Redirect to dashboard (replace with actual dashboard URL)
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Invalid username or password.<br>";
                }
            } else {
                echo "No user found with that username.<br>";
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error preparing the SQL query
            echo "Error: Could not prepare the query. " . $conn->error . "<br>";
        }
    }
}

// Close the database connection **only once** after everything is done
$conn->close();
?>
