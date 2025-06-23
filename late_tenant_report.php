<?php
include 'db_config.php'; // DB connection

date_default_timezone_set('UTC');
$today = new DateTime();

// Query to join tenants with their latest payment
$sql = "SELECT t.*, 
               p.payment_date, 
               p.payment_status 
        FROM add_tenant t
        LEFT JOIN (
            SELECT p1.*
            FROM payments p1
            INNER JOIN (
                SELECT select_tenant, MAX(payment_date) as latest_date
                FROM payments
                GROUP BY select_tenant
            ) latest
            ON p1.select_tenant = latest.select_tenant AND p1.payment_date = latest.latest_date
        ) p ON t.t_no = p.select_tenant";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Late Rent Payments</title>
  <style>
    .late { background-color: #ffdddd; }
    .paid { background-color: #ffffcc; }
  </style>
</head>
<body>

<h2>Late Payment Tenants</h2>

<table border="1">
  <thead>
    <tr>
      <th>Tenant Name</th>
      <th>Building</th>
      <th>Last Payment Date</th>
      <th>Rent Amount</th>
      <th>Status</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Payment Status</th>
    </tr>
  </thead>
  <tbody>

<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $fullName = $row["fname"] . " " . $row["lname"];
    $building = $row["select_building"];
    $rentAmount = $row["rent_amt"];
    $phone = $row["phoneno"];
    $email = $row["email"];
    $paySchedule = strtolower($row["pay_schedule"]);
    $paymentStatus = isset($row["payment_status"]) ? $row["payment_status"] : "No Payment";
    
    if (!$row["payment_date"]) {
        continue; // Skip tenants with no payment records
    }

    $lastPayDate = new DateTime($row["payment_date"]);

    // Calculate expected next due date
    $nextDueDate = clone $lastPayDate;
    switch ($paySchedule) {
      case 'monthly':
        $nextDueDate->modify('+1 month');
        break;
      case 'weekly':
        $nextDueDate->modify('+1 week');
        break;
      case 'annually':
        $nextDueDate->modify('+1 year');
        break;
      default:
        continue ;
    }

    $rowClass = '';
    $status = '';

    if ($today > $nextDueDate) {
      $delay = $today->diff($nextDueDate)->days;
      $status = "Late by $delay days";
      $rowClass = 'late';
    } elseif (strtolower($paymentStatus) === 'paid') {
      $status = "Paid";
      $rowClass = 'paid';
    }

    if ($status) {
      echo "<tr class='$rowClass'>
        <td>$fullName</td>
        <td>$building</td>
        <td>" . $lastPayDate->format('Y-m-d') . "</td>
        <td>$rentAmount</td>
        <td>$status</td>
        <td>$phone</td>
        <td>$email</td>
        <td>$paymentStatus</td>
      </tr>";
    }
  }
} else {
  echo "<tr><td colspan='8'>No tenants found.</td></tr>";
}

$conn->close();
?>

  </tbody>
</table>
</body>
</html>
