<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Total Finance Report By Owner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h3 {
            text-align: center;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: white;
            text-align: left;
        }

        td, th {
            padding: 10px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .no-print {
            margin-top: 20px;
        }

        .no-print button, .no-print a {
            margin-right: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .no-print button:hover, .no-print a:hover {
            background-color: #218838;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Total Finance Report By Owner</h3>

    <?php
include("db_config.php");

$owner_name = "Not selected";

if (isset($_GET['select_owner'])) {
    $owner_id = $_GET['select_owner'];

    // Fetch owner details
    $owner_result = mysqli_query($conn, "SELECT oname FROM owner_info WHERE owner_id = '$owner_id'");
    if ($owner_result && mysqli_num_rows($owner_result) > 0) {
        $owner_row = mysqli_fetch_assoc($owner_result);
        $owner_name = $owner_row['oname'];

        echo "<p><strong>Owner Information:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Name:</strong> " . $owner_name . "</li>";
        echo "</ul>";
    }
  }

   echo "<p>Report for Owner: <strong>$owner_name</strong></p>";
?>


    <p>Generated on <span id="generated-date"></span></p>

    <script>
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        };
        document.getElementById('generated-date').textContent = now.toLocaleString('en-US', options);

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Report link copied to clipboard!');
            });
        }
    </script>

      <div class="no-print">
        <a href="mailto:?subject=Vendor Information Report&body=View the vendor information report here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            📤 Share via Email
        </a>
        <button class="copy-btn" onclick="copyLink()">📋 Copy Report Link</button>
        <button onclick="window.print()" class="print-btn">🖨️ Print / Save as PDF</button>
        <!-- <a href="dashboard.php"> <button>Back</button> </a> -->

        <?php
          $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
          echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
        
    </div>

<?php

$monthly_data = [];
$total_tenant = 0;
$total_vendor = 0;
$total_all = 0;

// Define all 12 months
$months = [
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
];

// Get all service areas from vendor_info
$vendor_services = [];
$result = mysqli_query($conn, "SELECT DISTINCT service_area FROM vendor_info");
while ($row = mysqli_fetch_assoc($result)) {
    $vendor_services[] = $row['service_area'];
}

// Get all payments
$payments_result = mysqli_query($conn, "SELECT property_name, amount_paid, pay_date FROM payments_info");

while ($row = mysqli_fetch_assoc($payments_result)) {
    $month = date('m', strtotime($row['pay_date']));
    $property_name = $row['property_name'];
    $amount = floatval($row['amount_paid']);

    if (!isset($monthly_data[$month])) {
        $monthly_data[$month] = ['tenant' => 0, 'vendor' => 0, 'total' => 0];
    }

    $is_vendor = in_array($property_name, $vendor_services);

    if ($is_vendor) {
        $monthly_data[$month]['vendor'] += $amount;
        $total_vendor += $amount;
    } else {
        $monthly_data[$month]['tenant'] += $amount;
        $total_tenant += $amount;
    }

    $monthly_data[$month]['total'] += $amount;
    $total_all += $amount;
}
?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Month</th>
            <th>Tenant Payment</th>
            <th>Vendor Payment</th>
            <th>Total Payment</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($months as $num => $name): ?>
            <?php
                $tenant = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['tenant'], 2) : '000000';
                $vendor = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['vendor'], 2) : '000000';
                $total  = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['total'], 2)  : '000000';
            ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td><?php echo ($tenant == '0.00') ? '000000' : $tenant; ?></td>
                <td><?php echo ($vendor == '0.00') ? '000000' : $vendor; ?></td>
                <td><?php echo ($total == '0.00') ? '000000' : $total; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="font-weight:bold;">
            <td>Total</td>
            <td><?php echo $total_tenant == 0 ? '000000' : number_format($total_tenant, 2); ?></td>
            <td><?php echo $total_vendor == 0 ? '000000' : number_format($total_vendor, 2); ?></td>
            <td><?php echo $total_all == 0 ? '000000' : number_format($total_all, 2); ?></td>
        </tr>
    </tfoot>
</table>

    
</div>

</body>
</html>
