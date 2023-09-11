<?php
require 'config.php';
$conn=connectDB();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    
    try {
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $statement = $conn->prepare($query);
        $statement->bind_param('sss', $name, $email, $password);
        $statement->execute();
        
        echo "User registered successfully!";
        header("Location: signin.html");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
