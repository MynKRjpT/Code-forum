<?php
$alert = false;
$datainerror = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '_dbconnect.php';

    // Validate input fields
    $email = $_POST["email"];

  
    $username = $_POST["username"];


    $password = $_POST["password"];


    $cpassword = $_POST["cpassword"];


    if (empty($name) || empty($username) || empty($password) || empty($cpassword)) {
        $datainerror = "All fields are required.";
    } else {
        // Check if passwords match
        if ($password !== $cpassword) {
            $datainerror = "Both Passwords do not match...";
        } else {
            // Checking if username already exists
            $existSql = "SELECT * FROM sign WHERE username='$username'";
            $result = mysqli_query($conn, $existSql);
            $numExistRows = mysqli_num_rows($result);

            if ($numExistRows > 0) {
                $datainerror = "Username Already Exists. Please try another username.";
            } else {
                // Hash the password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                // Insert user data into the database
                $sql = "INSERT INTO `users` (`email`, `username`, `password`,`cpassword`, `timestamp`) VALUES ('$email', '$username', '$hash','$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $alert = true;
                } else {
                    $datainerror = "An error occurred. Please try again later.";
                }
            }
        }
    }
}
?>