<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Income Report By Owner</title>
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
    <h3>Income Report by Owner</h3>

    <!-- <div class="addbtn"> <a href="add_owners.php">➕</a> </div> -->

    <?php
    include("db_config.php");

    $owner_name = "Not selected";
    $total_income = 0;

    if (isset($_GET['select_owner'])) {
        $owner_id = $_GET['select_owner'];

        // Fetch owner details
        $owner_result = mysqli_query($conn, "SELECT oname, phoneno, address, email FROM owner_info WHERE owner_id = '$owner_id'");
        if ($owner_result && mysqli_num_rows($owner_result) > 0) {
            $owner_row = mysqli_fetch_assoc($owner_result);
            $owner_name = $owner_row['oname'];

            echo "<p><strong>Owner Information:</strong></p>";
            echo "<ul>";
            echo "<li><strong>Name:</strong> " . $owner_name . "</li>";
            echo "<li><strong>Phone:</strong> " . $owner_row['phoneno'] . "</li>";
            
            echo "<li><strong>Location:</strong> " . $owner_row['address'] .  "</li>";
            echo "<li><strong>Email:</strong> " . $owner_row['email'] . "</li>";

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
        <a href="mailto:?subject=Income Report for <?php echo urlencode($owner_name); ?>&body=Hi,%0A%0AHere is the income report for <?php echo urlencode($owner_name); ?>.%0A%0AView it here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            📧 Share via Email
        </a>
        <button onclick="copyLink()">📋 Copy Report Link</button>
        <button onclick="window.print()">🖨️ Print / Save as PDF</button>
        <!-- <a href="dashboard.php"> <button>Back</button> </a> -->
        <?php
          $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
          echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
        ?>
    </div>

    <table>
        <tr>
            <th>Property Name</th>
            <th>Tenant Name</th>
            <th>Date Paid</th>
            <th>Amount Paid</th>
        </tr>

        <?php
        if (isset($_GET['select_owner'])) {
            $owner_id = $_GET['select_owner'];

            $sql = "
                      SELECT 
                          bi.building_name, 
                          p.tenant_name, 
                          p.pay_date, 
                          p.amount_paid 
                      FROM 
                          payments_info AS p
                      INNER JOIN 
                          building_info AS bi 
                          ON p.property_name = bi.building_name
                      WHERE 
                          bi.select_owner = '$owner_id'
                      ";

            $query = mysqli_query($conn, $sql);

            if (mysqli_num_rows($query) > 0) {
                while ($res = mysqli_fetch_array($query)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($res['building_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($res['tenant_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($res['pay_date']) . "</td>";
                    echo "<td>₹ " . number_format($res['amount_paid'], 2) . "</td>";
                    echo "</tr>";

                    $total_income += $res['amount_paid'];
                }

                echo "<tr style='font-weight: bold; background-color: #e2e3e5;'>";
                echo "<td colspan='3' align='right'>Total Income:</td>";
                echo "<td>₹ " . number_format($total_income, 2) . "</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='4'>No payments found for this owner.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No owner selected.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
