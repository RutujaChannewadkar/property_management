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
    <h3 class="text-center">Vendor Login</h3>
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
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === "vendor" && $password === "vendor123") {
            echo "<div class='alert alert-success text-center' style='margin-top:15px;'>Login successful!</div>";
            // Redirect to dashboard or any page you want:
            echo "<script>setTimeout(function(){ window.location='add_vendor2.php'; }, 1500);</script>";
        } else {
            echo "<div class='alert alert-danger text-center' style='margin-top:15px;'>Invalid credentials. Try again.</div>";
        }
    }
    ?>
</div>

</body>
</html>
