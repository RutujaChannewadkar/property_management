<?php
include("db_config.php");

// Ensure ID is set
if (!isset($_GET['id'])) {
    die("Vendor ID not specified.");
}

$vendor_id = intval($_GET['id']);
$vendor = null;

// Fetch vendor info
$result = mysqli_query($conn, "SELECT * FROM vendor_info WHERE v_id = $vendor_id");
if ($result && mysqli_num_rows($result) > 0) {
    $vendor = mysqli_fetch_assoc($result);
} else {
    die("Vendor not found.");
}

// Fetch building names for dropdown
$buildingOptions = "";
$sql_buildings = "SELECT b_no, building_name FROM building_info ORDER BY building_name";
$result_buildings = mysqli_query($conn, $sql_buildings);
if ($result_buildings && mysqli_num_rows($result_buildings) > 0) {
    while ($row = mysqli_fetch_assoc($result_buildings)) {
        $selected = ($vendor['service_area'] === $row['building_name']) ? 'selected' : '';
        $buildingOptions .= "<option value='" . htmlspecialchars($row['building_name']) . "' $selected>" . htmlspecialchars($row['building_name']) . "</option>";
    }
} else {
    $buildingOptions = "<option value=''>No buildings found</option>";
}

// Update logic
if (isset($_POST["update"])) {
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

    $update_sql = "UPDATE vendor_info SET
        vendor_name = '$vendor_name',
        business_name = '$business_name',
        service_type = '$service_type',
        email = '$email',
        phone = '$phone',
        website = '$website',
        address = '$address',
        service_area = '$service_area',
        date_of_services = '$date_of_services',
        available_time_from = '$available_time_from',
        available_time_to = '$available_time_to',
        base_fee = '$base_fee',
        tax_fee = '$tax_fee',
        total_fee = '$total_fee',
        notes = '$notes'
        WHERE id = $vendor_id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<div class='text-center text-success'>Vendor updated successfully.</div>";
        // Refresh data
        $vendor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vendor_info WHERE id = $vendor_id"));
    } else {
        echo "<div class='text-center text-danger'>Update failed. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Vendor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
        .form-group label { font-weight: 600; }
        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 5px;
        }
        .btn-custom:hover { background-color: #0056b3; }
        .btn-secondary-custom {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 5px;
        }
        .two-columns {
            display: flex;
            gap: 20px;
        }
        .two-columns .form-group {
            flex: 1;
        }
    </style>

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
</head>
<body>

<div class="form-container">
    <h2>Update Vendor</h2>

    <form method="post">
        <div class="form-group">
            <label>Vendor Name*</label>
            <input type="text" name="vendor_name" class="form-control" value="<?= htmlspecialchars($vendor['vendor_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Business Name</label>
            <input type="text" name="business_name" class="form-control" value="<?= htmlspecialchars($vendor['business_name']) ?>">
        </div>

        <div class="form-group">
            <label>Service Type</label>
            <select name="service_type" class="form-control" required>
                <option value="">-- Select Service --</option>
                <?php
                $services = ["Plumbing", "Electrical", "Cleaning", "Landscaping", "Security", "Painting"];
                foreach ($services as $service) {
                    $selected = ($vendor['service_type'] === $service) ? "selected" : "";
                    echo "<option value='$service' $selected>$service</option>";
                }
                ?>
            </select>
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($vendor['email']) ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($vendor['phone']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="text" name="website" class="form-control" value="<?= htmlspecialchars($vendor['website']) ?>">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($vendor['address']) ?></textarea>
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
            <input type="date" name="date_of_services" class="form-control" value="<?= $vendor['date_of_services'] ?>">
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Available Time From</label>
                <input type="time" name="available_time_from" class="form-control" value="<?= $vendor['available_time_from'] ?>">
            </div>
            <div class="form-group">
                <label>Available Time To</label>
                <input type="time" name="available_time_to" class="form-control" value="<?= $vendor['available_time_to'] ?>">
            </div>
        </div>

        <div class="two-columns">
            <div class="form-group">
                <label>Base Fee</label>
                <input type="number" step="0.01" name="base_fee" id="base_fee" class="form-control" value="<?= $vendor['base_fee'] ?>">
            </div>
            <div class="form-group">
                <label>Tax Fee</label>
                <input type="number" step="0.01" name="tax_fee" id="tax_fee" class="form-control" value="<?= $vendor['tax_fee'] ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Total Fee</label>
            <input type="number" step="0.01" name="total_fee" id="total_fee" class="form-control" value="<?= $vendor['total_fee'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($vendor['notes']) ?></textarea>
        </div>

        <div class="form-group text-center">
            <a href="dashboard.php#vendor" class="btn btn-secondary-custom">Go back</a>
            <button type="submit" name="update" class="btn btn-custom">Update</button>
        </div>
    </form>
</div>

<script>
    const baseFee = document.getElementById("base_fee");
    const taxFee = document.getElementById("tax_fee");
    const totalFee = document.getElementById("total_fee");

    function updateTotal() {
        const base = parseFloat(baseFee.value) || 0;
        const tax = parseFloat(taxFee.value) || 0;
        totalFee.value = (base + tax).toFixed(2);
    }

    baseFee.addEventListener("input", updateTotal);
    taxFee.addEventListener("input", updateTotal);
</script>

</body>
</html>
