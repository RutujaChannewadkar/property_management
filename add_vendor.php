<?php
include("db_config.php"); // Make sure this file contains your DB connection logic


if (isset($_POST["submit"])) {
    $vendor_name = $_POST['vendor_name'];
    $business_name = $_POST['business_name'];
    $service_type = $_POST['service_type'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $address = $_POST['address'];
    $service_area = $_POST['service_area'];
    $date_of_services = $_POST['date_of_services'];
    $available_time_from = $_POST['available_time_from'];
    $available_time_to = $_POST['available_time_to'];
    $base_fee = $_POST['base_fee'];
    $tax_fee = $_POST['tax_fee'];
    $total_fee = $_POST['total_fee'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO vendor_info(
        vendor_name, business_name, service_type, email, phone, website, address,
        service_area, date_of_services, available_time_from, available_time_to,
        base_fee, tax_fee, total_fee, notes
    ) VALUES (
        '$vendor_name', '$business_name', '$service_type', '$email', '$phone', '$website', '$address',
        '$service_area', '$date_of_services', '$available_time_from', '$available_time_to',
        '$base_fee', '$tax_fee', '$total_fee', '$notes'
    )";

    if (mysqli_query($conn, $sql)) {
        $successMessage = "Vendor added successfully!";
    } else {
        $errorMessage = "Error inserting data:";
    }
}

       // Fetch building names for service_area dropdown
       $buildingOptions = "";
       $sql_buildings = "SELECT b_no, building_name FROM building_info ORDER BY building_name";
       $result_buildings = mysqli_query($conn, $sql_buildings);

       if ($result_buildings && mysqli_num_rows($result_buildings) > 0) {
           while ($row = mysqli_fetch_assoc($result_buildings)) {
               $buildingOptions .= "<option value='" . htmlspecialchars($row['building_name']) . "'>" . htmlspecialchars($row['building_name']) . "</option>";
             }
       } else {
           $buildingOptions = "<option value=''>No buildings found</option>";
       }
?>


<!DOCTYPE html>
<html>
<head>
     <title>Add Vendor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script>
      window.onload = function () {
     var baseFee = document.getElementById("base_fee");
    var taxFee = document.getElementById("tax_fee");
    var totalFee = document.getElementById("total_fee");

    function updateTotal() {
        var base = parseFloat(baseFee.value) || 0;
        var tax = parseFloat(taxFee.value) || 0;
        totalFee.value = (base + tax).toFixed(2);
    }

    baseFee.addEventListener("input", updateTotal);
    taxFee.addEventListener("input", updateTotal);
};

    
         
    function validate() {
        const form = document.forms["vendorForm"];

        const vendorName = form["vendor_name"].value.trim();
        const businessName = form["business_name"].value.trim();
        const email = form["email"].value.trim();
        const phone = form["phone"].value.trim();
        const address = form["address"].value.trim();
        const serviceType = form["service_type"].value;
        const serviceArea = form["service_area"].value;
        const dateOfServices = form["date_of_services"].value;
        const timeFrom = form["available_time_from"].value;
        const timeTo = form["available_time_to"].value;
        const baseFee = form["base_fee"].value;
        const taxFee = form["tax_fee"].value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // const phoneRegex = /^\d{7,15}$/;
        const phoneRegex = /^\d{10}$/;
        var data = /^[A-Za-z]+$/;


        if (!vendorName) {
            alert("Vendor Name is required.");
            return false;
        }
        if (!data.test(vendorName)) {
            alert("Vendor name should only contain alphabetic characters.");
            return false;
        }

        if (!businessName) {
            alert("Business Name is required.");
            return false;
        }

         if (!serviceType) {
            alert("Please select a Service Type.");
            return false;
        }

        if (!email || !emailRegex.test(email)) {
            alert("Please enter a valid Email.");
            return false;
        }

        if (!phone || !phoneRegex.test(phone)) {
            alert("Please enter a valid Phone number (10 digits).");
            return false;
        }

        if (!address) {
            alert("Address is required.");
            return false;
        }

        if (!serviceType) {
            alert("Please select a Service Type.");
            return false;
        }

        if (!serviceArea) {
            alert("Please select a Service Area.");
            return false;
        }

        if (!dateOfServices) {
            alert("Please select Date of Services.");
            return false;
        } else {
            const today = new Date().toISOString().split("T")[0];
            if (dateOfServices < today) {
                alert("Date of Services cannot be in the past.");
                return false;
            }
        }

        if (!timeFrom || !timeTo) {
            alert("Both Available Time From and To are required.");
            return false;
        }

        if (timeFrom >= timeTo) {
            alert("Available Time From must be earlier than Available Time To.");
            return false;
        }

        if (baseFee === "" || isNaN(baseFee) || parseFloat(baseFee) < 0) {
            alert("Base Fee must be a valid non-negative number.");
            return false;
        }

        if (taxFee === "" || isNaN(taxFee) || parseFloat(taxFee) < 0) {
            alert("Tax Fee must be a valid non-negative number.");
            return false;
        }

        return true;
    }
    
</script>

   
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
            max-width: 720px;
            margin: 60px auto;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 5px;
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

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .text-success { color: green; }
        .text-danger { color: red; }

        .two-columns {
            display: flex;
            gap: 20px;
        }

        .two-columns .form-group {
            flex: 1;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Vendor</h2>


    <form method="post" id="vendorForm"  onsubmit="return validate();">
        <div class="form-group">
            <label>Vendor Name*</label>
            <input type="text" name="vendor_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Business Name</label>
            <input type="text" name="business_name" class="form-control">
        </div>

        <div class="form-group">
            <label>Service Type</label>
            <select name="service_type" class="form-control" required>
                <option value="">-- Select Service --</option>
                <option>Plumbing</option>
                <option>Electrical</option>
                <option>Cleaning</option>
                <option>Landscaping</option>
                <option>Security</option>
                <option>Painting</option>
            </select>
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="number" name="phone" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="text" name="website" class="form-control">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="form-group">
             <label>Service Area</label>
             <select name="service_area" class="form-control">
                 <option value="">-- Select Building --</option>
                 <?= $buildingOptions ?>
             </select>
        </div>


        <div class="form-group">
            <label>Date of Services</label>
            <input type="date" name="date_of_services" class="form-control">
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Available Time From</label>
                <input type="time" name="available_time_from" class="form-control">
            </div>
            <div class="form-group">
                <label>Available Time To</label>
                <input type="time" name="available_time_to" class="form-control">
            </div>
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Base Fee</label>
                <input type="number" step="0.01" name="base_fee" id="base_fee" class="form-control">
            </div>
            <div class="form-group">
                <label>Tax Fee</label>
                <input type="number" step="0.01" name="tax_fee" id="tax_fee" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Total Fee</label>
            <input type="number" step="0.01" name="total_fee" id="total_fee" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="form-group text-center">
            <!-- <a href="dashboard.php" class="btn btn-secondary-custom">Go back</a> -->
            <?php
                 $section = isset($_GET['section']) ? $_GET['section'] : 'vendor';
                echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
             ?>

            <button type="submit" name="submit" class="btn btn-custom" onclick="return validate()">Submit</button>
            <button type="reset" class="btn btn-secondary-custom">Reset</button>
        </div>
    </form>
</div>

</body>
</html>
