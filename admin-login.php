<?php

require_once 'config.php'; // Include the config file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Create a database connection
    $conn = connectDB();

    // Perform login validation and database checks
    $query = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    echo $result->num_rows;
    if ($result->num_rows >0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"])) {
            // Login successful
            $_SESSION["user_id"] = $user["id"];
            header("Location: dashboard.php"); // Redirect to dashboard
            echo $_SERVER["REQUEST_METHOD"];

            exit();
        } else {
            header("Location: signin.html"); // Redirect to dashboard

            $error_message = "Invalid password";
        }
    } else {
        header("Location: signin.html"); // Redirect to dashboard
    }

    $stmt->close();
    $conn->close();
}
?>