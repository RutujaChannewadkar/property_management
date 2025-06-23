<?php
include("db_config.php");

// Get selected month and year if submitted
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Format start and end of the month
$start_date = "$selected_year-$selected_month-01";
$end_date = date("Y-m-t", strtotime($start_date));

$query = "SELECT * FROM payments 
          WHERE payment_date BETWEEN '$start_date' AND '$end_date'
          ORDER BY payment_date ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Payment Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .report-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="report-container">
    <h3 class="text-center">Payment Report for <?php echo date("F Y", strtotime($start_date)); ?></h3>

    <form method="get" class="form-inline text-center" style="margin-bottom: 20px;">
        <div class="form-group">
            <label for="month">Select Month:</label>
            <select name="month" class="form-control" required>
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $month_value = str_pad($m, 2, '0', STR_PAD_LEFT);
                    $selected = $month_value == $selected_month ? "selected" : "";
                    echo "<option value='$month_value' $selected>" . date("F", mktime(0, 0, 0, $m, 10)) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="year">Year:</label>
            <select name="year" class="form-control" required>
                <?php
                $current_year = date('Y');
                for ($y = $current_year; $y >= $current_year - 5; $y--) {
                    $selected = $y == $selected_year ? "selected" : "";
                    echo "<option value='$y' $selected>$y</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View Report</button>
        <!-- <a href="dashboard.php#payment" class="btn btn-default">Back to Dashboard</a> -->
        <?php
            $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
            echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Tenant Name</th>
                    <th>Property</th>
                    <th>Unit</th>
                    <th>Rent Amount</th>
                    <th>Late Fee</th>
                    <th>Total Paid</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Receipt #</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['payment_date']; ?></td>
                        <td><?php echo $row['tenant_name']; ?></td>
                        <td><?php echo $row['property_name']; ?></td>
                        <td><?php echo $row['unit_no']; ?></td>
                        <td>Rs. <?php echo number_format($row['amount_paid']); ?></td>
                        <td>Rs. <?php echo number_format($row['late_fee']); ?></td>
                        <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
                        <td><?php echo ucfirst($row['payment_method']); ?></td>
                        <td><span class="label label-<?php echo $row['payment_status'] == 'paid' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($row['payment_status']); ?>
                        </span></td>
                        <td><?php echo $row['receipt_no']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">No payments found for the selected month.</div>
    <?php endif; ?>
</div>
</body>
</html>
