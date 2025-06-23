<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Report By Properties</title>
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

        .no-print button.print-btn {
            background-color: #6c757d;
        }

        .no-print button.copy-btn {
            background-color: #28a745;
        }

        .no-print a:hover, .no-print button:hover {
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
    <h3>Income Report by Properties</h3>

    <?php
    include("db_config.php");

    $building_name = "Not selected";
    $total_income = 0;

    if (isset($_GET['select_building'])) {
        $building_id = mysqli_real_escape_string($conn, $_GET['select_building']);

        $building_result = mysqli_query($conn, "SELECT building_name FROM building_info WHERE b_no = '$building_id'");
        if ($building_result && mysqli_num_rows($building_result) > 0) {
            $building_row = mysqli_fetch_assoc($building_result);
            $building_name = htmlspecialchars($building_row['building_name']);
        }
    }

    echo "<p><strong>Report for Property:</strong> $building_name</p>";
    ?>

    <p><strong>Generated on:</strong> <span id="generated-date"></span></p>

    <script>
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
        <a href="mailto:?subject=Income Report for <?php echo urlencode($building_name); ?>&body=View the income report here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            📤 Share via Email
        </a>
        <button class="copy-btn" onclick="copyLink()">📋 Copy Report Link</button>
        <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>

        <?php
            $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
            echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date Paid</th>
                <th>Tenant Name</th>
                <th>Unit No.</th>
                <th>Amount Paid</th>
            </tr>
        </thead>
        <tbody>
    <?php  
            if (isset($_GET['select_building'])) {
    $building_id = mysqli_real_escape_string($conn, $_GET['select_building']);

    // Get building name from building_info
    $building_name_result = mysqli_query($conn, "SELECT building_name FROM building_info WHERE b_no = '$building_id'");
    $building_name = "";
    if ($building_name_result && mysqli_num_rows($building_name_result) > 0) {
        $building_row = mysqli_fetch_assoc($building_name_result);
        $building_name = $building_row['building_name'];
    }

    $sql = "SELECT pay_date AS payment_date, tenant_name, unit_no, amount_paid 
            FROM payments_info 
            WHERE property_name = '" . mysqli_real_escape_string($conn, $building_name) . "' 
              AND approval_status = 'Approved'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($res = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($res['payment_date']) . "</td>";
            echo "<td>" . htmlspecialchars($res['tenant_name']) . "</td>";
            echo "<td>" . htmlspecialchars($res['unit_no']) . "</td>";
            echo "<td>₹ " . number_format($res['amount_paid'], 2) . "</td>";
            echo "</tr>";
            $total_income += $res['amount_paid'];
        }

        echo "<tr class='total-row'>";
        echo "<td colspan='3' align='right'>Total Income:</td>";
        echo "<td>₹ " . number_format($total_income, 2) . "</td>";
        echo "</tr>";
    } else {
        echo "<tr><td colspan='4'>No paid payments found for this building.</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>No building selected.</td></tr>";
}

?>

        </tbody>
    </table>
</div>

</body>
</html>
