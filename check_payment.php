<?php
session_start();
include("db_config.php");

// // Redirect if not logged in
// if (!isset($_SESSION['tenant'])) {
//     header("Location: tenant_login.php");
//     exit();
// }

// Handle approval of payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_payment'])) {
    $payment_id = intval($_POST['payment_id']);
    $conn->query("UPDATE payments_info SET approval_status = 'Approved' WHERE pay_id = $payment_id");
}

// Show year-wise status per tenant
if (isset($_GET['tenant_name'])) {

    $tenant_name = $conn->real_escape_string($_GET['tenant_name']);
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    $rent_amt = 0;
    $maintenance_amt = 0;
    $movein_date = null;

    $rent_sql = "SELECT rent_amt, movein FROM add_tenant WHERE tname = '$tenant_name' LIMIT 1";
    $rent_result = $conn->query($rent_sql);

    if ($rent_result && $rent_result->num_rows > 0) {
        $rent_row = $rent_result->fetch_assoc();
        $rent_amt = floatval($rent_row['rent_amt']);
        $movein_date = $rent_row['movein'];
    }

    if (!$movein_date) {
        $movein_date = "$year-01-01";
    }

    $movein_timestamp = strtotime($movein_date);
    $movein_year = intval(date('Y', $movein_timestamp));
    $movein_month = intval(date('n', $movein_timestamp));

    // Fetch payments for tenant for the given year
    $sql = "
        SELECT * FROM payments_info
        WHERE tenant_name = '$tenant_name'
        AND YEAR(pay_date) = $year
    ";
    $result = $conn->query($sql);

    $payments_by_month = [];
    $has_check_payment = false;

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $month_num = intval(date('n', strtotime($row['pay_date'])));
            $payments_by_month[$month_num][] = $row;

            if (strtolower($row['pay_method']) === 'check') {
                $has_check_payment = true;
            }
        }
    }

    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Status for <?= htmlspecialchars($tenant_name); ?> in <?= $year; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .table-box { width: 95%; margin: 50px auto; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: red; font-weight: bold; }
        form.inline { display: inline; }
    </style>
</head>
<body>

<div class="table-box">
    <h3 class="text-center">Payment Status for <?= htmlspecialchars($tenant_name); ?> - Year <?= $year; ?></h3>
    <p class="text-center"><strong>Monthly Rent:</strong> ₹<?= number_format($rent_amt, 2); ?></p>

    <hr>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Month</th>
                <th>Payment Date</th>
                <th>Property Name</th>
                <th>Unit No</th>
                <th>Amount Paid</th>
                <th>Pending Amount</th>
                <th>Method</th>
                <?php if ($has_check_payment): ?><th>Check No</th><?php endif; ?>
                <th>Receipt</th>
                <th>Status / Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $carried_forward = 0;
        $grand_total_paid = 0;

        foreach ($payments_by_month as $month_num => $payment_records):
            $month_name = date('F', mktime(0, 0, 0, $month_num, 1, $year));
            $monthly_paid = 0;

            foreach ($payment_records as $payment) {
                $monthly_paid += floatval($payment['amount_paid']);
            }

            $total_due = $rent_amt + $maintenance_amt + $carried_forward;
            $pending_amount = max(0, $total_due - $monthly_paid);

            foreach ($payment_records as $payment):
                $grand_total_paid += floatval($payment['amount_paid']);
        ?>
            <tr>
                <td><?= $month_name; ?></td>
                <td><?= htmlspecialchars($payment['pay_date']); ?></td>
                <td><?= htmlspecialchars($payment['property_name']); ?></td>
                <td><?= htmlspecialchars($payment['unit_no']); ?></td>
                <td><?= htmlspecialchars($payment['amount_paid']); ?></td>
                <td><?= number_format($pending_amount, 2); ?></td>
                <td><?= htmlspecialchars($payment['pay_method']); ?></td>
                <?php if ($has_check_payment): ?>
                    <td><?= strtolower($payment['pay_method']) === 'check' ? htmlspecialchars($payment['check_no']) : ''; ?></td>
                <?php endif; ?>
                <td>
                     <?php if (!empty($payment['receipt_path'])): ?>
                       <a href="<?= htmlspecialchars($payment['receipt_path']); ?>" target="_blank">View</a>
                     <?php else: ?>N/A<?php endif; ?>
                </td>
                <td class="<?= (!empty($payment['approval_status']) && $payment['approval_status'] === 'Approved') ? 'status-approved' : 'status-pending'; ?>">
                    <?php if (!empty($payment['approval_status']) && $payment['approval_status'] === 'Approved'): ?>
                        Approved
                    <?php else: ?>
                        Not Approved
                        <form method="POST" class="inline" style="margin-left: 10px;">
                            <input type="hidden" name="payment_id" value="<?= $payment['pay_id']; ?>">
                            <button type="submit" name="approve_payment" class="btn btn-xs btn-success">Approve</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php
            endforeach;
            $carried_forward = $pending_amount;
        endforeach;

        $total_due_for_year = ($rent_amt + $maintenance_amt) * count($payments_by_month);
        $total_pending = max(0, $total_due_for_year - $grand_total_paid);
        ?>
        <tr style="font-weight: bold; background-color: #f9f9f9;">
            <td colspan="4" class="text-right">Total</td>
            <td><?= number_format($grand_total_paid, 2); ?></td>
            <td><?= number_format($total_pending, 2); ?></td>
            <td colspan="<?= $has_check_payment ? 3 : 2 ?>"></td>
            <td></td>
        </tr>
        </tbody>
    </table>

    <?php
        $section = isset($_GET['section']) ? $_GET['section'] : 'payment';
        echo "<a href='dashboard.php#{$section}' class='btn btn-primary'>Go back</a>";
    ?>

</div>

</body>
</html>

<?php exit(); } ?>
