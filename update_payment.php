<?php
include("db_config.php");

// Get pay_id from query string
if (!isset($_GET['pay_id'])) {
    die("Missing payment ID.");
}
$pay_id = intval($_GET['pay_id']);

// Fetch existing payment info
$payment_query = mysqli_query($conn, "SELECT * FROM payments WHERE pay_id = $pay_id");
if (!$payment_query || mysqli_num_rows($payment_query) == 0) {
    die("Payment not found.");
}
$payment = mysqli_fetch_assoc($payment_query);

// Handle form submission
if (isset($_POST['submit'])) {
    $select_tenant = $_POST['select_tenant'];
    $property_name = $_POST['property_name'];
    $unit_no = $_POST['unit_no'];
    $building_id = $_POST['building_id'];
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];
    $late_fee = $_POST['late_fee'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $receipt_no = $_POST['receipt_no'];
    $notes = $_POST['notes'];

    $tenant_result = mysqli_query($conn, "SELECT CONCAT(fname, ' ', lname) AS tenant_name FROM add_tenant WHERE t_no = '$select_tenant'");
    $tenant = mysqli_fetch_assoc($tenant_result);
    $tenant_name = $tenant['tenant_name'];

    $update_sql = "UPDATE payments 
                   SET select_tenant = '$select_tenant',
                       tenant_name = '$tenant_name',
                       property_name = '$property_name',
                       unit_no = '$unit_no',
                       building_id = $building_id,
                       payment_date = '$payment_date',
                       amount_paid = '$amount_paid',
                       late_fee = '$late_fee',
                       total_amount = '$total_amount',
                       payment_method = '$payment_method',
                       payment_status = '$payment_status',
                       receipt_no = '$receipt_no',
                       notes = '$notes'
                   WHERE pay_id = $pay_id";

    echo mysqli_query($conn, $update_sql)
        ? "<div class='alert alert-success text-center'>Payment Updated Successfully!</div>"
        : "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";

    // Refresh data after update
    $payment_query = mysqli_query($conn, "SELECT * FROM payments WHERE pay_id = $pay_id");
    $payment = mysqli_fetch_assoc($payment_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
    </style>

    <script type="text/javascript">
        
         function validate() 
    {
          var data = /^[A-Za-z]+$/;

      // validation 
      if (document.f1.select_tenant.value == "") {
        alert("Please Select Tenant Name");
        document.f1.select_tenant.focus();
        return false;
      }

      if (document.f1.payment_method.value == "") {
        alert("Please Select Payment Method");
        document.f1.payment_method.focus();
        return false;
      }

      if (document.f1.payment_status.value !== "paid") 
      {
        alert("Please select 'Paid' as the Payment Status.");
        document.f1.payment_status.focus();
        return false;
      }

      if (document.f1.receipt_no.value == "") {
        alert("Please Enter Receipt Number");
        document.f1.receipt_no.focus();
        return false;
      }
  }

    </script>
</head>
<body>
<div class="form-container">
    <form method="post">
        <h3 class="text-center">Update Payment</h3>

        <div class="form-group">
            <label>Select Tenant</label>
            <select name="select_tenant" class="form-control" required>
                <option value="">-- Select Tenant --</option>
                <?php
                $tenants = mysqli_query($conn, "SELECT t_no, CONCAT(fname, ' ', lname) AS name FROM add_tenant");
                while ($row = mysqli_fetch_assoc($tenants)) {
                    $selected = $payment['select_tenant'] == $row['t_no'] ? 'selected' : '';
                    echo "<option value='{$row['t_no']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Property Name</label>
            <input type="text" name="property_name" class="form-control" required value="<?= htmlspecialchars($payment['property_name']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Unit Number</label>
            <input type="text" name="unit_no" class="form-control" required value="<?= htmlspecialchars($payment['unit_no']) ?>" readonly>
        </div>

        <input type="hidden" name="building_id" value="<?= $payment['building_id'] ?>" readonly>

        <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" required value="<?= $payment['payment_date'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Amount Paid</label>
            <input type="number" name="amount_paid" class="form-control" required value="<?= $payment['amount_paid'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Late Fee</label>
            <input type="number" name="late_fee" class="form-control" value="<?= $payment['late_fee'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Total Amount</label>
            <input type="number" name="total_amount" class="form-control" required value="<?= $payment['total_amount'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="">Select Method</option>
                <option value="cash" <?= $payment['payment_method'] == 'cash' ? 'selected' : '' ?>>Cash</option>
                <option value="bank_transfer" <?= $payment['payment_method'] == 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                <option value="credit_card" <?= $payment['payment_method'] == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
            </select>
        </div>

        <div class="form-group">
            <label>Payment Status</label>
            <select name="payment_status" class="form-control">
                <option value="unpaid" <?= $payment['payment_status'] == 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                <option value="paid" <?= $payment['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
            </select>
        </div>

        <div class="form-group">
            <label>Receipt No</label>
            <input type="text" name="receipt_no" class="form-control" required value="<?= htmlspecialchars($payment['receipt_no']) ?>">
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($payment['notes']) ?></textarea>
        </div>

        <div class="form-group text-center">
            <a href='dashboard.php#payment' class='btn btn-default'>Go back</a>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
</body>
</html>
