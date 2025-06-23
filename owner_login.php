<?php
session_start(); // Start the session to track user login

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for correct credentials
    if ($username === "Owner" && $password === "Owner123") {
        // Store the username in session and redirect
        $_SESSION['username'] = $username;
        // Redirect to the dashboard after a short delay
        echo "<script>
                setTimeout(function(){
                    document.getElementById('loginForm').style.display = 'none'; // Hide login form
                    document.getElementById('successMessage').style.display = 'block'; // Show success message
                    document.getElementById('loginHeading').style.display = 'none'; // Hide the login heading
                    setTimeout(function(){
                        window.location = 'dashboard.php';
                    }, 500); // Redirect after 1.5 seconds
                }, 500); // Short delay before hiding the form and showing the success message
              </script>";
    } else {
        $error_message = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        #successMessage {
            display: none;
            text-align: center;
            padding: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center" id="loginHeading">Owner Login</h3> <!-- ID added to hide heading -->
    <hr>

    <form method="post" id="loginForm">
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

    <!-- Success message -->
    <div id="successMessage">
        <p>Login Successful! 🎉✅</p>
    </div>

    <?php
    // Show error message if login failed
    if (isset($error_message)) {
        echo "<div class='alert alert-danger text-center' style='margin-top:15px;'>$error_message</div>";
    }
    ?>
</div>

</body>
</html>
