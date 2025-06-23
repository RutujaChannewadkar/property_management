<!DOCTYPE html>
<html>
<head>
    <title>Simple Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center">Tenant Login</h3>
    <hr>

    <form method="post">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
          <center> <a href="index.php#logindiv">⬅️</a> </center>
    </form>

    <?php
session_start();
include("db_config.php");

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM add_tenant WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $tenant = $result->fetch_assoc();

        // Store all tenant data in session
        $_SESSION['tenant'] = $tenant;

        echo "<div class='alert alert-success text-center' style='margin-top:15px;'>Login successful! Redirecting...</div>";
        echo "<script>setTimeout(function(){ window.location='add_payment.php'; }, 1500);</script>";
    } else {
        echo "<div class='alert alert-danger text-center' style='margin-top:15px;'>Invalid credentials. Try again.</div>";
    }
}
?>



</div>

</body>
</html>

