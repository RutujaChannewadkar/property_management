<?php
include("db_config.php");

// Query to fetch vendor information from the 'vendor_info' table
$query = "SELECT * FROM vendor_info ORDER BY vendor_name";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching vendor data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Information Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        
        }

        .container {
            max-width: 1500px;
            margin: auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /*p {
            margin: 10px 0;
            color: #555;
        }*/

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 5px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        td {
            background-color: #f9f9f9;
            font-size: 14px;
            color: #555;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e2e6ea;
        }

        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .no-print {
            margin-top: 20px;
            display: flex;
/*            justify-content: space-between;*/
            gap: 15px;
            flex-wrap: wrap;
        }

        .no-print a, .no-print button {
            background-color: #007bff;
            color: white;
            padding:5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .no-print button.print-btn {
            background-color: #f0ad4e;
        }

        .no-print button.copy-btn {
            background-color: #28a745;
        }

        .no-print a:hover, .no-print button:hover {
            opacity: 0.8;
        }

        .no-print a:active, .no-print button:active {
            opacity: 1;
        }

        .no-print button:focus {
            outline: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            table {
                width: 100%;
                margin-top: 30px;
                border: 1px solid #ddd;
            }


            th {
                background-color: #007bff;
                color: white;
            }

            td {
                background-color: #f9f9f9;
                color: #555;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Vendor Information Report</h3>

    <p><strong>Report Generated on:</strong> <span id="generated-date"></span></p>

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

    <table>
        <thead>
            <tr>
                <th>Vendor Name</th>
                <th>Business Name</th>
                <th>Service Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Website</th>
                <th>Address</th>
                <th>Service Area</th>
                <th>Date of Services</th>
                <th>Available Time From</th>
                <th>Available Time To</th>
                <th>Base Fee</th>
                <th>Tax Fee</th>
                <th>Total Fee</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($vendor = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($vendor['vendor_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['business_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['service_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['website']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['service_area']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['date_of_services']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['available_time_from']) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['available_time_to']) . "</td>";
                    echo "<td>₹ " . number_format($vendor['base_fee'], 2) . "</td>";
                    echo "<td>₹ " . number_format($vendor['tax_fee'], 2) . "</td>";
                    echo "<td>₹ " . number_format($vendor['total_fee'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($vendor['notes']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15' style='text-align:center;'>No vendor records found.</td></tr>";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

</div>

</body>
</html>
