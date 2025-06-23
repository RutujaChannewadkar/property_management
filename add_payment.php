<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['tenant'])) {
    header("Location: tenant_login.php");
    exit();
}

$tenant = $_SESSION['tenant'];
$tenant_name = $tenant['tname'];
$property_name = $tenant['building_name'];
$unit_no = $tenant['unit_no'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenant Information & Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .info-box {
            width: 700px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            border-radius: 10px;
        }
        .form-section {
            margin-top: 30px;
        }
    </style>

   <script type="text/javascript">
function validate() {
    const paymentDate = document.querySelector('input[name="payment_date"]').value;
    const amountPaid = document.querySelector('input[name="amount_paid"]').value;
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const checkNoInput = document.querySelector('input[name="check_no"]');
    const fileInput = document.querySelector('input[name="payment_receipt"]');

    let methodSelected = false;
    let selectedMethod = '';

    // Validate Payment Date
    if (!paymentDate) {
        alert("Please select a payment date.");
        return false;
    }

    // Validate Amount
    if (!amountPaid || amountPaid <= 0) {
        alert("Please enter a valid amount.");
        return false;
    }

    // Validate Payment Method
    for (const method of paymentMethods) {
        if (method.checked) {
            methodSelected = true;
            selectedMethod = method.value;
            break;
        }
    }

    if (!methodSelected) {
        alert("Please select a payment method.");
        return false;
    }

    // If method is "Check", ensure check number is filled
    if (selectedMethod === "Check") {
        if (!checkNoInput.value.trim()) {
            alert("Please enter the check number.");
            checkNoInput.focus();
            return false;
        }
    }

    // Validate file upload
    if (fileInput.files.length === 0) {
        alert("Please upload a receipt.");
        return false;
    } else {
        const file = fileInput.files[0];
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

        if (!allowedTypes.includes(file.type)) {
            alert("Invalid file type. Only PDF, JPG, JPEG, and PNG are allowed.");
            return false;
        }
    }

    return true;
}
</script>


</head>
<body>
<div class="info-box">
    <h3 class="text-center">Tenant Information</h3>
    <hr>
    <table class="table table-bordered">
        <tr><th>Name</th><td><?php echo htmlspecialchars($tenant_name); ?></td></tr>
        <tr><th>Property Name</th><td><?php echo htmlspecialchars($property_name); ?></td></tr>
        <tr><th>Unit No</th><td><?php echo htmlspecialchars($unit_no); ?></td></tr>
    </table>

    <div class="form-section">
    
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Payment Date:</label>
                <input type="date" name="payment_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Amount Paid:</label>
                <input type="number" name="amount_paid" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Payment Method:</label><br>
                <label><input type="radio" name="payment_method" value="Cash" required> Cash</label><br>
                <label><input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer</label><br>
                <label><input type="radio" name="payment_method" value="Online Payment"> Online Payment</label><br>
                <label><input type="radio" name="payment_method" value="Check"> Check (No. 
                    <input type="text" name="check_no" class="form-control" style="display:inline-block; width: 200px; margin-left: 5px;">
                )</label>
            </div>
 
           <div class="form-group">
                <label>Upload Receipt:</label>
                <input type="file" name="payment_receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
            </div> 

            <!-- Hidden fields to send tenant info -->
            <input type="hidden" name="tenant_name" value="<?php echo htmlspecialchars($tenant_name); ?>">
            <input type="hidden" name="property_name" value="<?php echo htmlspecialchars($property_name); ?>">
            <input type="hidden" name="unit_no" value="<?php echo htmlspecialchars($unit_no); ?>">

            <button type="submit" name="submit" class="btn btn-primary btn-block" onclick="return validate()">Submit Payment</button>
            <a href="index.php" class="btn btn-danger btn-block" style="margin-top: 15px;">Logout</a>


        </form>
    </div>


    <?php
    if (isset($_POST["submit"]))
     {

      include("db_config.php");

      $payment_date = $_POST["payment_date"];
      $tenant_name = $_POST["tenant_name"];
      $property_name = $_POST["property_name"];
      $unit_no = $_POST["unit_no"];
       
      $amount_paid = $_POST["amount_paid"];
      $payment_method = $_POST["payment_method"];
      $check_no=$_POST["check_no"];
      // $payment_receipt = $_POST["payment_receipt"];
      // File upload handling
      $receipt_path = '';
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['payment_receipt']['tmp_name'];
      $fileName = $_FILES['payment_receipt']['name'];
      $fileSize = $_FILES['payment_receipt']['size'];
      $fileType = $_FILES['payment_receipt']['type'];
      $fileNameCmps = explode(".", $fileName);
      $fileExtension = strtolower(end($fileNameCmps));

      $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
      if (in_array($fileExtension, $allowedfileExtensions)) {
          $uploadFileDir = 'uploads/';
          if (!is_dir($uploadFileDir)) {
               mkdir($uploadFileDir, 0777, true); // Create uploads folder if not exists
          }
          $newFileName = uniqid() . '.' . $fileExtension;
          $dest_path = $uploadFileDir . $newFileName;
  
          if(move_uploaded_file($fileTmpPath, $dest_path)) {
              $receipt_path = $dest_path;
          } else {
                echo "<div class='text-center text-danger'><strong>Error moving uploaded file.</strong></div>";
            }
        } else {
            echo "<div class='text-center text-danger'><strong>Upload failed. Allowed types: jpg, jpeg, png, pdf.</strong></div>";
        }
    }



      $sql = "INSERT INTO payments_info (pay_date, tenant_name, property_name, unit_no, amount_paid, pay_method, check_no, receipt_path) 
        VALUES ('$payment_date', '$tenant_name', '$property_name', '$unit_no', '$amount_paid', '$payment_method', '$check_no', '$receipt_path')";


      if (mysqli_query($conn, $sql)) {
        echo "<div class='text-center text-success'><strong>Data Inserted Successfully!</strong></div>";
      } else {
        echo "<div class='text-center text-danger'><strong>Data Not Inserted.</strong></div>";
      }
    }
    ?>
</div>
</body>
</html>
      
