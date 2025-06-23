<?php
include("db_config.php");

// Fetch all tenant information from the database
$query = "SELECT * FROM add_tenant";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching tenant data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tenant Information Report</title>
    <style>
        <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    h3 {
        text-align: center;
        color: #222;
        font-size: 24px;
        margin-bottom: 20px;
    }

   /* p {
        margin: 5px 0;
        font-size: 14px;
    }*/

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        font-size: 13px;
    }

    th, td {
        padding: 10px 12px;
        border: 1px solid #d1d1d1;
        text-align: left;
        vertical-align: top;
    }

    th {
        background-color: #007bff;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #eef5ff;
    }

    .no-print {
/*        margin-bottom: 20px;*/
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .no-print a, .no-print button {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .no-print button.copy-btn {
        background-color: #28a745;
    }

    .no-print a:hover, .no-print button:hover {
        background-color: #0056b3;
    }

    .no-print button.copy-btn:hover {
        background-color: #218838;
    }

    @media screen and (max-width: 768px) {
        th, td {
            font-size: 12px;
            padding: 8px;
        }

        h3 {
            font-size: 20px;
        }

        .no-print {
            flex-direction: column;
            align-items: flex-start;
        }

        .no-print a, .no-print button {
            width: 100%;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background-color: #fff;
            padding: 0;
        }

        .container {
            box-shadow: none;
            padding: 10px;
        }

        th {
            background-color: #000 !important;
            color: #fff !important;
        }

        table {
            font-size: 11px;
        }
    }
</style>

    </style>
</head>
<body>

<div class="container">
    <h3>All Tenant Information Report</h3>

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

        //searchbar
       
     function filterTable() {
         const input = document.getElementById("searchBuilding");
         const filter = input.value.toLowerCase();
         const table = document.querySelector("table");
         const tr = table.getElementsByTagName("tr");

         for (let i = 1; i < tr.length; i++) {
             const td = tr[i].getElementsByTagName("td")[2]; // 3rd column = Building Name
             if (td) {
                 const txtValue = td.textContent || td.innerText;
                 tr[i].style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
             }
         }
     }

    </script>

    


    <div class="no-print">

          <div style="margin-top: 20px; text-align: right;">
    <label for="searchBuilding" style="font-weight: bold;">Search by Property Name: </label>
    <input type="text" id="searchBuilding" placeholder="Enter Property name..." onkeyup="filterTable()" style="padding: 6px 10px; width: 250px; border: 1px solid #ccc; border-radius: 5px;">
      </div>

        <!-- <a href="mailto:?subject=All Tenant Information Report&body=View the full tenant information report here: <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"> -->
            <!-- 📤 Share via Email -->
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
        <th>Tenant ID</th>
        <th>Building ID</th>
        <th>Building Name</th>
        <th>Unit No.</th>
        <th>Registration Date</th>
        <th>Registration Time</th>
        <th>Tenant Name</th>
        <th>Username</th>
        <th>Tenant Address</th>
        <th>Email</th>
        <th>Password</th>
        <th>Phone No</th>
        <th>Age</th>
        <th>PAN No</th>
        <th>Aadhar No</th>
        <th>Identity</th>
        <th>Rent Amount</th>
        <th>Deposit</th>
        <th>Move-in</th>
        <th>Move-out</th>
        <th>Deposit Date</th>
        <th>Payment Schedule</th>
        <th>Next Pay Date</th>
        <th>Lease Start</th>
        <th>Lease End</th>
        <th>Lease Period</th>
        <th>Lease End Date</th>
        <th>Police Verification</th>
        <th>Emergency Contact Name</th>
        <th>Relation</th>
        <th>Emergency Phone</th>
        <th>Emergency Address</th>
        <th>ID Proof File</th>
        <th>Agreement Upload</th>
        <th>Company Name</th>
        <th>Partnership Type</th>
        <th>Company Address</th>
        <th>Partner Name</th>
        <th>Partner Address</th>
        <th>Partner Phone No</th>
        <th>Partner Email</th>
    </tr>
</thead>

        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
    while ($tenant = mysqli_fetch_assoc($result)) {
        $full_name = $tenant['tname'];

echo "<tr>";
echo "<td>" . htmlspecialchars($tenant['t_no']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['select_building']) . "</td>";

// Fetch building name using join or secondary query
$building_name = "Unknown";
if (!empty($tenant['select_building'])) {
    $building_id = $tenant['select_building'];
    $building_query = "SELECT building_name FROM building_info WHERE b_no = '$building_id'";
    $building_result = mysqli_query($conn, $building_query);
    if ($building_result && mysqli_num_rows($building_result) > 0) {
        $building_row = mysqli_fetch_assoc($building_result);
        $building_name = $building_row['building_name'];
    }
}
echo "<td>" . htmlspecialchars($building_name) . "</td>";

echo "<td>" . htmlspecialchars($tenant['unit_no']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['reg_date']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['reg_time']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['tname']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['username']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['tenant_address']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['email']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['password']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['phoneno']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['age']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['pan_no']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['aadhar_no']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['identi']) . "</td>";
echo "<td>₹ " . number_format($tenant['rent_amt'], 2) . "</td>";
echo "<td>₹ " . number_format($tenant['diposit'], 2) . "</td>";
echo "<td>" . htmlspecialchars($tenant['movein']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['moveout']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['diposit_date']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['pay_schedule']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['pay_date']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['t_start']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['t_end']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['lease_period']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['t_end_date']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['police']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['emg_name']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['relation']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['emg_phone']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['emg_add']) . "</td>";

// ID proof file
echo "<td>";
if (!empty($tenant['fileupload'])) {
    echo "<a href='" . htmlspecialchars($tenant['fileupload']) . "' target='_blank'>View</a>";
} else {
    echo "Not uploaded";
}
echo "</td>";

// Agreement file
echo "<td>";
if (!empty($tenant['agree_upload'])) {
    echo "<a href='" . htmlspecialchars($tenant['agree_upload']) . "' target='_blank'>View</a>";
} else {
    echo "Not uploaded";
}
echo "</td>";

echo "<td>" . htmlspecialchars($tenant['company_name']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['partnership_type']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['company_address']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['partner_name']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['partner_address']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['partner_phoneno']) . "</td>";
echo "<td>" . htmlspecialchars($tenant['partner_email']) . "</td>";

echo "</tr>";

                }
            }
            else {
                echo "<tr><td colspan='8' style='text-align:center;'>No tenants found in the system.</td></tr>";
            }

            
            ?>
        </tbody>
    </table>

</div>

</body>
</html>
