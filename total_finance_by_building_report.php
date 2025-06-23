<?php
include("db_config.php");

$building_name = "Not selected";
$building_id = null;

if (isset($_GET['select_building'])) {
    $building_id = $_GET['select_building'];

    // Fetch building details
    $building_result = mysqli_query($conn, "SELECT building_name FROM building_info WHERE b_no = '$building_id'");
    if ($building_result && mysqli_num_rows($building_result) > 0) {
        $building_row = mysqli_fetch_assoc($building_result);
        $building_name = $building_row['building_name'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Total Finance Report By Building</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <h3>Total Finance Report By Building</h3>

    <p><strong>Building:</strong> <?php echo $building_name; ?></p>
    <p>Generated on <span id="generated-date"></span></p>

    <script>
        document.getElementById('generated-date').textContent = new Date().toLocaleString('en-US');
    </script>

      <div class="no-print">
        <a href="mailto:?subject=Vendor Information Report&body=View the vendor information report here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            📤 Share via Email
        </a>
        <button class="copy-btn" onclick="copyLink()">📋 Copy Report Link</button>
        <button onclick="window.print()" class="print-btn">🖨️ Print / Save as PDF</button>
        <!-- <a href="dashboard.php"> <button>Back</button> </a>     -->
        <?php
          $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
          echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
    </div>


    <?php
    if ($building_id) {
        // Define months
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        // Get vendor service areas
        $vendor_service_areas = [];
        $vendor_result = mysqli_query($conn, "SELECT DISTINCT service_area FROM vendor_info");
        while ($row = mysqli_fetch_assoc($vendor_result)) {
            $vendor_service_areas[] = $row['service_area'];
        }

        echo "<h4>Finance Report for Building: $building_name</h4>";

        // Initialize monthly data
        $monthly_data = [];
        $total_tenant = 0;
        $total_vendor = 0;
        $total_all = 0;

        // Get payments for selected building
       $payment_query = "
    SELECT p.property_name, p.amount_paid, p.pay_date as payment_date
    FROM payments_info p
    JOIN unit_info u ON p.unit_no = u.unit_no
    WHERE u.building_id = '$building_id'";
  // $payment_result = mysqli_query($conn, $payment_query);

        $payment_result = mysqli_query($conn, $payment_query);

        while ($row = mysqli_fetch_assoc($payment_result)) {
            $month = date('m', strtotime($row['payment_date']));
            $property_name = $row['property_name'];
            $amount = floatval($row['amount_paid']);

            if (!isset($monthly_data[$month])) {
                $monthly_data[$month] = ['tenant' => 0, 'vendor' => 0, 'total' => 0];
            }

            $is_vendor = in_array($property_name, $vendor_service_areas);

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

        echo "<table border='1' cellpadding='10' cellspacing='0'>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Tenant Payment</th>
                    <th>Vendor Payment</th>
                    <th>Total Payment</th>
                </tr>
            </thead>
            <tbody>";

        foreach ($months as $num => $name) {
            $tenant = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['tenant'], 2) : '0.00';
            $vendor = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['vendor'], 2) : '0.00';
            $total  = isset($monthly_data[$num]) ? number_format($monthly_data[$num]['total'], 2)  : '0.00';

            echo "<tr>
                    <td>$name</td>
                    <td>" . ($tenant == '0.00' ? '0.00' : $tenant) . "</td>
                    <td>" . ($vendor == '0.00' ? '0.00' : $vendor) . "</td>
                    <td>" . ($total == '0.00' ? '0.00' : $total) . "</td>
                  </tr>";
        }

        echo "</tbody>
            <tfoot>
                <tr style='font-weight: bold;'>
                    <td>Total</td>
                    <td>" . ($total_tenant == 0 ? '0.00' : number_format($total_tenant, 2)) . "</td>
                    <td>" . ($total_vendor == 0 ? '0.00' : number_format($total_vendor, 2)) . "</td>
                    <td>" . ($total_all == 0 ? '0.00' : number_format($total_all, 2)) . "</td>
                </tr>
            </tfoot>
        </table><br>";
    } else {
        echo "<p style='color:red;'>Please select a building to generate the report.</p>";
    }
    ?>
</div>
</body>
</html>
