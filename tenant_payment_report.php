<?php
include("db_config.php");

// Get tenant ID from URL parameter
$tenant_id = isset($_GET['select_tenant']) ? intval($_GET['select_tenant']) : 0;
if ($tenant_id <= 0) {
    die("Invalid or missing tenant ID.");
}

// Fetch tenant information from the database
$tenant_result = mysqli_query($conn, "SELECT * FROM add_tenant WHERE t_no = '$tenant_id'");
if (mysqli_num_rows($tenant_result) == 0) {
    die("Tenant not found.");
}
$tenant = mysqli_fetch_assoc($tenant_result);
$full_name = $tenant['tname'];
$escaped_name = $conn->real_escape_string($full_name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Payment Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        p {
            margin: 5px 0;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }

        .no-print {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .no-print a, .no-print button {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .no-print button.copy-btn {
            background-color: #28a745;
        }

        .no-print button:hover, .no-print a:hover {
            opacity: 0.9;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Tenant Payment Report</h3>

    <p><strong>Tenant Name:</strong> <?php echo htmlspecialchars($full_name); ?></p>
    <p><strong>Rent Amount:</strong> ₹<?php echo number_format($tenant['rent_amt'], 2); ?></p>
    <p><strong>Lease Period:</strong> <?php echo htmlspecialchars($tenant['t_start']); ?> to <?php echo htmlspecialchars($tenant['t_end_date']); ?></p>
    <p><strong>Generated on:</strong> <span id="generated-date"></span></p>

    <script>
        // Set the generated date
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        document.getElementById('generated-date').textContent = now.toLocaleString('en-US', options);

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert("Report link copied to clipboard!");
            }, () => {
                alert("Failed to copy the link.");
            });
        }
    </script>

    <div class="no-print">
        <a href="mailto:?subject=Tenant Payment Report for <?php echo urlencode($full_name); ?>&body=View the tenant payment report here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            📤 Share via Email
        </a>
        <button class="copy-btn" onclick="copyLink()">📋 Copy Report Link</button>
        <button onclick="window.print()">🖨️ Print / Save as PDF</button>
        <!-- <a href="dashboard.php"> <button>Back</button> </a> -->

        <?php
          $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
          echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
        
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Amount Paid</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Check No</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch payment details for the tenant
            $pay_sql = "SELECT * FROM payments_info WHERE tenant_name = '$escaped_name' ORDER BY pay_date";
            $payments = mysqli_query($conn, $pay_sql);
            $total_paid = 0;

            if ($payments && mysqli_num_rows($payments) > 0) {
                while ($pay = mysqli_fetch_assoc($payments)) {
                    $total_paid += $pay['amount_paid'];
                    // Determine display status for payment approval
                    $display_status = (strtolower($pay['approval_status']) === 'approved') ? 'Paid' : ucfirst($pay['approval_status']);

                    // Show check number only if payment method is 'check' (case-insensitive)
                   $payment_method_lower = strtolower($pay['pay_method']);
                   $check_number = ($payment_method_lower === 'check') ? htmlspecialchars($pay['check_no']) : '-----';

                    echo "<tr>
                            <td>" . htmlspecialchars($pay['pay_date']) . "</td>
                            <td>₹ " . number_format($pay['amount_paid'], 2) . "</td>
                            <td>" . htmlspecialchars($pay['pay_method']) . "</td>
                            <td>" . htmlspecialchars($display_status) . "</td>
                            <td>" . $check_number . "</td>
                          </tr>";
                }

                // Calculate balance
                $balance = ($tenant['rent_amt'] * 12) - $total_paid;
                echo "<tr class='total-row'>
                        <td colspan='5' align='right'>Total Paid:</td>
                        <td>₹ " . number_format($total_paid, 2) . "</td>
                      </tr>";
                echo "<tr class='total-row'>
                        <td colspan='5' align='right'>Estimated Balance (12 mo):</td>
                        <td>₹ " . number_format($balance, 2) . "</td>
                      </tr>";
            } else {
                echo "<tr><td colspan='6' style='color:red;'>No payments found for this tenant.</td></tr>";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
