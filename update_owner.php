<!DOCTYPE html>
<html>
<head>
  <title>Create Owner</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    body {
      background: #f0f2f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-container {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 40px;
      max-width: 500px;
      margin: 60px auto;
    }

    .form-container h3 {
      margin-bottom: 30px;
      color: #333;
      font-weight: bold;
    }

    .form-group input {
      height: 45px;
      font-size: 16px;
    }

    .btn-custom {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      margin: 5px;
      transition: background 0.3s ease;
    }

    .btn-custom:hover {
      background-color: #0056b3;
    }

    .btn-secondary-custom {
      background-color: #6c757d;
      color: white;
      padding: 10px 20px;
      border: none;
      margin: 5px;
    }

    .text-center {
      text-align: center;
    }
  </style>
  
  <script type="text/javascript">
    
    function validate() {
      var data = /^[A-Za-z]+$/;

      if (document.f1.oname.value == "") {
        alert("Please Enter Full Name");
        document.f1.oname.focus();
        return false;
      }

      if (!document.f1.oname.value.match(data)) {
        alert("Please enter valid Name");
        document.f1.oname.focus();
        return false;
      }

     

      if (document.f1.phoneno.value == "") {
        alert("Please Enter Phone Number");
        document.f1.phoneno.focus();
        return false;
      }
      if (document.f1.phoneno.value.length != 10) {
        alert("Please Enter 10 digit Phone Number");
        document.f1.phoneno.focus();
        return false;
      }

      if (document.f1.age.value == "") {
        alert("Please Enter Age ");
        document.f1.age.focus();
        return false;
      }

      if (document.f1.pan_no.value == "") {
        alert("Please Enter Pan Number ");
        document.f1.pan_no.focus();
        return false;
      }

      if (document.f1.aadhar_no.value == "") {
        alert("Please Enter Aadhar Number ");
        document.f1.aadhar_no.focus();
        return false;
      }

      if (document.f1.address.value == "") {
        alert("Please Enter Address ");
        document.f1.address.focus();
        return false;
      }

      if (document.f1.email.value == "") {
        alert("Please Enter Email ");
        document.f1.email.focus();
        return false;
      }
    }
  </script>

  
</head>
<body>

  <?php 
  
        
           include("db_config.php");

           $id=$_GET['id'];

           $sql="select * from owner_info where owner_id=$id";

           $query=mysqli_query($conn,$sql);

           while($row=mysqli_fetch_array($query))
           {

  ?>


  <div class="form-container">
    <form name="f1" method="post">
      <h3 class="text-center">Add Owner</h3>

      <div class="form-group">
        <input type="text" name="oname" placeholder="Owner Name" class="form-control" 
                value="<?php echo $row['oname'];?>" >
      </div>

      <div class="form-group">
        <input type="text" name="age" placeholder="Age " class="form-control"
                 value="<?php echo $row['age'];?>" >
      </div>

      <div class="form-group">
        <input type="number" name="phoneno" placeholder="Phone No" class="form-control"
                 value="<?php echo $row['phoneno'];?>" >
      </div>

      <div class="form-group">
        <input type="text" name="pan_no" placeholder="Pan Number" class="form-control"
                   value="<?php echo $row['pan_no'];?>" >
      </div>

      <div class="form-group">
        <input type="text" name="aadhar_no" placeholder="Aadhar Number" class="form-control"
                   value="<?php echo $row['aadhar_no'];?>">
      </div>

       <div class="form-group">
        <input type="text" name="address" placeholder="Address" class="form-control"
                   value="<?php echo $row['address'];?>">
      </div>

      <div class="form-group">
        <input type="email" name="email" placeholder="Email Id" class="form-control"
                 value="<?php echo $row['email'];?>">
      </div>


      <?php 
                 } //End of While
      ?> 


      <div class="form-group text-center">
        <a href="dashboard.php" class="btn btn-secondary-custom">Go back</a>
        <button type="submit" name="submit" class="btn btn-custom" onclick="return validate()">Update</button>
        <button type="reset" class="btn btn-secondary-custom">Reset</button>
      </div>
    </form>

    <?php
    if (isset($_POST["submit"])) {
      include("db_config.php");

      
      $oname = $_POST["oname"];
      $age = $_POST["age"];

      $phoneno = $_POST["phoneno"];
      $pan_no = $_POST["pan_no"];
      $aadhar_no=$_POST["aadhar_no"];
      $address = $_POST["address"];
      $email = $_POST["email"];



      $sql="update owner_info set oname='$oname',age='$age',phoneno='$phoneno',
                     pan_no='$pan_no',aadhar_no='$aadhar_no',address='$address',email='$email' where owner_id='$id'";



       if (mysqli_query($conn, $sql)) {
        // Success message and redirect using JavaScript
        echo "<div class='text-center text-success'><strong>Updated successfully!</strong></div>";
        echo "<script>setTimeout(function(){ window.location.href = 'dashboard.php'; }, 2000);</script>";
      } else {
        // Error message and redirect using JavaScript
        echo "<div class='text-center text-danger'><strong>Not updated. Please try again.</strong></div>";
        echo "<script>setTimeout(function(){ window.location.href = 'dashboard.php'; }, 2000);</script>";
      }              

                   
    }
    ?>

  </div>

</body>
</html>
