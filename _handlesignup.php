

<?php
session_start();
include '_dbconnect.php';

$alert = false;
$datainerror = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    if (empty($email) || empty($username) || empty($password) || empty($cpassword)) {
        $datainerror = "All fields are required.";
    } else {
        if ($password !== $cpassword) {
            $datainerror = "Both Passwords do not match...";
        } else {
            $existSql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $existSql);
            $numExistRows = mysqli_num_rows($result);

            if ($numExistRows > 0) {
                $datainerror = "Username Already Exists. Please try another username.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`email`, `username`, `password`, `timestamp`) VALUES ('$email', '$username', '$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $alert = true;
                    header("location:index.php");
                   
                } else {
                    $datainerror = "An error occurred. Please try again later.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
</head>
<body>
  
<?php if ($alert): ?>
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Successfully!</strong> Your account has been created. Now click on login to log in to your Account....
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>×</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($datainerror): ?>
    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
        <strong>Error!</strong> <?= $datainerror ?>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>×</span>
        </button>
    </div>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>