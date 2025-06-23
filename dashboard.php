<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Property Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style type="text/css">
    body {
      overflow-x: hidden;
       background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #343a40;
      color: white;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 12px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      padding: 20px;
    }
    .owner-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: 0.2s;
        }
        .owner-card:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .owner-name {
            font-size: 17px;
            color: black;
            text-decoration: none;
        }
        .owner-name:hover {
            text-decoration: none;
        }
        h2
        {
          padding: 10px;
        }

        .addbtn
        {
/*          padding:5px;*/
          margin-left: 92%;
          margin-top: -5%;
          margin-bottom: 5%;
          font-size: 28px;
        }
        a{
            text-decoration: none;

        }
        
/*        Report*/

     .form-group {
            padding: 20px;
            font-size: 20px;
            width: 40%;
            margin-left: 20%;
/*            margin-top: 30px;*/
            text-align: center;
        }
        .btn {
            margin-top: 15px;
        }
        .hidden {
            display: none;
        }
         .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            transition: background 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }



/*        Alert*/
       .alert-buttons {
          margin: 20px 0;
          display: flex;
          gap: 15px;
          flex-wrap: wrap;
        }

        .alert-btn {
          font-size: 16px;
          font-weight: 500;
          padding: 12px 20px;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .warning-btn {
          background-color: #BDDDE4;
          color: #333;
        }

        .warning-btn:hover {
          background-color: #1DCD9F;
          transform: scale(1.05);
        }

        .alert-btn:active {
          transform: scale(0.97);
        }

        .alert-badge {
           background-color: red;
           color: white;
           font-size: 10px;
           padding: 2px 6px;
           border-radius: 50%;
           position: absolute;
           margin-left: 80px;
           margin-top: -30px;
        }
        .alert-badge-btn {
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            margin-left: 5px;
        }
  </style>


  <script>

// Section navigation for main menu
    window.showSection =  function(id) {
      var sections = document.querySelectorAll('.section');
      sections.forEach(section => section.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }


      // function showSection(id) {
      //   var sections = document.querySelectorAll('.section');
      //   sections.forEach(section => section.style.display = 'none');
      //   document.getElementById(id).style.display = 'block';
      //   console.log("Switched to section:", id);  // Optional for debugging
      // }

// Subsection navigation for alerts
    window.showAlertSection = function(id) {
      var sections = document.querySelectorAll('#alerts > div');
      sections.forEach(section => section.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    };

    // // Default section when the page loads
    // window.onload = function() {
    //   showSection('owner');
    // };


    // Default section loader based on hash
    window.onload = function() {
  const section = window.location.hash ? window.location.hash.substring(1) : 'owner';
  showSection(section);
};


    ///Report

  //    $(document).ready(function () 
  // {
  //   // Function to show or hide select fields based on report type
  //   function showHideFields(reportType) {
  //     // Hide all initially and remove required
  //     $("#ownerSelectWrapper, #buildingSelectWrapper, #tenantSelectWrapper").addClass("hidden");
  //     $("#select_owner, #select_building, #select_tenant").prop("required", false);

  //     // Show relevant dropdown
  //     if (reportType === "Income Report by Owner" || reportType === "Expense Report By Owner" || reportType === "Total Finance By Owner") {
  //       $("#ownerSelectWrapper").removeClass("hidden");
  //       $("#select_owner").prop("required", true);
  //     } else if (reportType === "Income Report by Building" || reportType === "Expense Report By Building" || reportType === "Total Finance By Building" || reportType === "Rent Roll") {
  //       $("#buildingSelectWrapper").removeClass("hidden");
  //       $("#select_building").prop("required", true);
  //     } else if (reportType === "Tenant Payment Report") {
  //       $("#tenantSelectWrapper").removeClass("hidden");
  //       $("#select_tenant").prop("required", true);
  //     }
  //   }

  //   // Trigger the function on change of report type
  //   $('select[name="report_type"]').on('change', function () {
  //     const selected = $(this).val();
  //     showHideFields(selected);
  //   });
  // });

      
  </script>
<?php
include("db_config.php");

$unapproved_count = 0;

$query = "
    SELECT COUNT(*) as count 
    FROM payments_info 
    WHERE amount_paid > 0 
      AND (approval_status IS NULL OR approval_status != 'Approved')
";

$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unapproved_count = intval($row['count']);
}
?>


<?php
include("db_config.php");

// === Count Expired Leases ===
$today = date('Y-m-d');
$expired_lease_count = 0;

$leaseQuery = "
    SELECT COUNT(*) as count 
    FROM add_tenant 
    WHERE t_end_date < '$today'
";
$leaseResult = mysqli_query($conn, $leaseQuery);
if ($leaseResult && $row = mysqli_fetch_assoc($leaseResult)) {
    $expired_lease_count = intval($row['count']);
}

// === Count Late Tenants ===
$late_tenant_count = 0;

$tenantQuery = "SELECT * FROM add_tenant";
$tenantResult = mysqli_query($conn, $tenantQuery);

function getPeriodWindow($startDate, $schedule) {
    $now = new DateTime();
    $start = new DateTime($startDate);
    switch (strtolower($schedule)) {
        case 'monthly': $interval = new DateInterval('P1M'); break;
        case 'quarterly': $interval = new DateInterval('P3M'); break;
        case 'half-yearly': $interval = new DateInterval('P6M'); break;
        case 'yearly': $interval = new DateInterval('P1Y'); break;
        default: $interval = new DateInterval('P1M'); break;
    }

    while ($start <= $now) {
        $start->add($interval);
    }

    $start->sub($interval);
    $end = clone $start;
    $end->add($interval)->sub(new DateInterval('P1D'));

    return [$start->format('Y-m-d'), $end->format('Y-m-d')];
}

if ($tenantResult && mysqli_num_rows($tenantResult) > 0) {
    while ($tenant = mysqli_fetch_assoc($tenantResult)) {
        $payDate = $tenant['pay_date'];
        $schedule = $tenant['pay_schedule'];

        if (!$payDate || !$schedule) continue;

        list($periodStart, $periodEnd) = getPeriodWindow($payDate, $schedule);
        $graceStart = (new DateTime($periodStart))->sub(new DateInterval('P15D'))->format('Y-m-d');
        $graceEnd = (new DateTime($periodEnd))->add(new DateInterval('P15D'))->format('Y-m-d');

        $tname = $conn->real_escape_string($tenant['tname']);
        $building = $conn->real_escape_string($tenant['building_name']);
        $unit = $conn->real_escape_string($tenant['unit_no']);

        $checkPayment = "
            SELECT 1 FROM payments_info 
            WHERE tenant_name = '$tname'
              AND property_name = '$building'
              AND unit_no = '$unit'
              AND approval_status = 'Approved'
              AND pay_date BETWEEN '$graceStart' AND '$graceEnd'
            LIMIT 1
        ";

        $hasPaid = mysqli_query($conn, $checkPayment);
        if ($hasPaid && mysqli_num_rows($hasPaid) === 0) {
            $late_tenant_count++;
        }
    }
}

// Total alert count
$total_alert_count = $late_tenant_count + $expired_lease_count;
?>


</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 sidebar d-flex flex-column">
        <h4 class="text-center mt-3"> Dashboard</h4>
        <a href="#" onclick="showSection('owner')">🧑‍💼 Owner</a>
        <a href="#" onclick="showSection('build')">🏢 Property</a>
        <a href="#" onclick="showSection('tenant')">👥 Tenant</a>
        <a href="#" onclick="showSection('vendor')">🪪 Vendor</a>

        <?php if ($unapproved_count > 0): ?>
        <style>
            .notify-badge {
                background-color: red;
                color: white;
                border-radius: 50%;
                padding: 2px 6px;
                font-size: 10px;
                position: absolute;
                margin-left: 100px;
                margin-top: -30px;
            }
            .notify-wrapper {
                position: relative;
                display: inline-block;
            }
        </style>
        <div class="notify-wrapper">
            <a href="#" onclick="showSection('payment')">💳 Payment</a>
            <span class="notify-badge"><?= $unapproved_count; ?></span>
        </div>
    <?php else: ?>
        <a href="#" onclick="showSection('payment')">💳 Payment</a>
    <?php endif; ?>

        <a href="#" onclick="showSection('reports')">📈 Reports</a>
        <a href="#" onclick="showSection('generateAgreement')">📄 Generate Agreement</a>

        <div class="notify-wrapper" style="position:relative;">
          <a href="#" onclick="showSection('alerts')">🚨 Alerts</a>
          <?php if ($total_alert_count > 0): ?>
            <span class="alert-badge"><?= $total_alert_count; ?></span>
          <?php endif; ?>
        </div>

        <a href="owner_login.php">  ⬅️ Exit</a>
      </nav>

      

      <!-- Main Content -->
      <main class="col-md-10 content">


<!-- ------------------------OWNER--------------------------------- -->

        <div id="owner" class="section"> 
          <h2>Owner</h2>
          <!-- <p>List of buildings and their details.</p> -->
        
          <div class="addbtn"> <a href="add_owners.php">➕</a> </div>
        
        <!-- Owner List from DB -->
          <?php
             include("db_config.php");

             $result = mysqli_query($conn, "SELECT owner_id, oname FROM owner_info");

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $owner_id = $row['owner_id'];
                  $full_name = $row['oname'];
              echo "
                   <div class='owner-card'>
                      <a class='owner-name' href='update_owner.php?id=$owner_id'>
                        $full_name
                      </a>
                   </div>
                  ";
            }
           } else {
         echo "<p>No Owners found.</p>";
       }
          ?>
        </div>



<!-- ------------------------Property--------------------------------- -->


       <div id="build" class="section" style="display:none;">
          <h2>Property</h2>
          <div class="addbtn"> <a href="add_building.php?section=build">➕</a> </div>
        
            <!-- Property List from DB -->
          <?php
             include("db_config.php");

             $result = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $b_id = $row['b_no'];
                  $b_name = $row['building_name'];
              echo "
                   <div class='owner-card'>
                      <a class='owner-name' href='update_building.php?id=$b_id'>
                        $b_name
                      </a>
                   </div>
                  ";
            }
           } else {
                 echo "<p>No Building found.</p>";
                  }
          ?>
          
        </div>


<!-- ------------------------TENANT--------------------------------- -->


        <div id="tenant" class="section" style="display:none;">
          <h2>Tenant</h2>
          <!-- <p>Manage tenants, leases, and contact info.</p> -->

          <div class="addbtn"> <a href="add_tenant.php?section=tenant">➕</a> </div>
        
                  <!-- Tenant List from DB -->
          <?php
             include("db_config.php");

             $result = mysqli_query($conn, "SELECT t_no, tname FROM add_tenant");

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $tenant_id = $row['t_no'];
                  $full_name = $row['tname'];
              echo "
                   <div class='owner-card'>
                      <a class='owner-name' href='update_tenant.php?id=$tenant_id'>
                        $full_name
                      </a>
                   </div>
                  ";
            }
           } else {
         echo "<p>No Tenant found.</p>";
       }
          ?>

        </div>



  <!-- ------------------------Vendor-------------------------------- -->

      <div id="vendor" class="section" style="display:none;"> 
          <h2>Vendor</h2>
        
          <div class="addbtn"><a href="add_vendor.php?section=vendor">➕</a> </div>
        
         <!-- Vendor List from DB -->
          <?php
             include("db_config.php");

             $result = mysqli_query($conn, "SELECT v_id, vendor_name FROM vendor_info");

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $vendor_id = $row['v_id'];
                  $full_name = $row['vendor_name'];
              echo "
                   <div class='owner-card'>
                      <a class='owner-name' href='update_vendor.php?id=$vendor_id'>
                        $full_name
                      </a>
                   </div>
                  ";
            }
           } else {
         echo "<p>No Vendor found.</p>";
       }
          ?>
        </div>



<!-- ------------------------PAYMENT--------------------------------- -->


        <?php
            include("db_config.php");

            $unapproved_count = 0;
            $payments_by_year = [];

            $query = "
                SELECT pay_id, tenant_name, property_name, unit_no, approval_status, pay_date 
                FROM payments_info 
                WHERE amount_paid > 0 
                ORDER BY YEAR(pay_date) DESC, pay_date DESC
            ";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $year = date('Y', strtotime($row['pay_date']));
                    if (!isset($payments_by_year[$year])) {
                        $payments_by_year[$year] = [];
                    }
            
                    if (empty($row['approval_status']) || $row['approval_status'] !== 'Approved') {
                        $row['unapproved'] = true;
                        $unapproved_count++;
                    } else {
                        $row['unapproved'] = false;
                    }

                    $payments_by_year[$year][] = $row;
                }
            }
        ?>

      <div id="payment" class="section" style="display:none;">
      <h2>Payments</h2>
    

      <?php if (!empty($payments_by_year)): ?>
        <?php foreach ($payments_by_year as $year => $yearly_payments): ?>
            <h4><?= $year; ?></h4>
            <?php foreach ($yearly_payments as $payment): ?>
                <div class="owner-card" style="<?= $payment['unapproved'] ? 'background-color:#f8d7da;' : ''; ?>">

                      
   <a class="owner-name" href="check_payment.php?tenant_name=<?= urlencode($payment['tenant_name']); ?>&year=<?= date('Y', strtotime($payment['pay_date'])); ?>">

                 <?= htmlspecialchars($payment['tenant_name']) . " - " . htmlspecialchars($payment['property_name']) . " - Unit " . htmlspecialchars($payment['unit_no']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No completed payments found.</p>
    <?php endif; ?>
</div>

 
<!-- ------------------------REPORT--------------------------------- -->
<div class="form-container section" id="reports" style="display:none;">
    <h2>Generate Report</h2>

  <!-- Report Type Dropdown -->
    <form id="reportForm" method="get">
        <!-- Report Type -->
        <div class="form-group">
            <select name="report_type" id="report_type" class="form-control" required>
                <option disabled selected value="">Select type of report</option>
                <option value="income_by_building.php">Income Report By Building</option>
                <option value="total_finance_by_building_report.php">Total Finance By Building</option>
                <option value="income_by_owner.php">Income Report By Owner</option>
                <option value="total_finance_by_owner_report.php">Total Finance By Owner</option>
                <option value="tenant_payment_report.php">Tenant Payment Report</option>
                <!-- <option value="late_tenant_report.php">Late Tenant Report</option> -->
                <option value="tenant_information_report.php">All Tenant Information</option>
                <option value="vendor_information_report.php">Vendor Information</option>
                <!-- <option value="payment_report.php">Payment Report (Monthly)</option> -->
                <option value="occupancy_details.php">Occupancy Report</option>
                <option value="invoice_report.php">Invoice Report</option>

            </select>
        </div>

        <!-- Select Owner -->
        <div class="form-group hidden" id="ownerSelectWrapper">
            <select name="select_owner" id="select_owner" class="form-control">
                <option disabled selected value="">Select Owner</option>
                <?php
                include("db_config.php");
                $result = mysqli_query($conn, "SELECT owner_id, oname FROM owner_info");
                while ($row = mysqli_fetch_assoc($result)) {
                    $owner_id = $row['owner_id'];
                    $full_name = $row['oname'];
                    echo "<option value='$owner_id'>$full_name</option>";
                }
                ?>
            </select>
        </div>

        <!-- Select Building -->
        <div class="form-group hidden" id="buildingSelectWrapper">
            <select name="select_building" id="select_building" class="form-control">
                <option disabled selected value="">Select Building</option>
                <?php
                $result = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");
                while ($row = mysqli_fetch_assoc($result)) {
                    $building_id = $row['b_no'];
                    $building_name = $row['building_name'];
                    echo "<option value='$building_id'>$building_name</option>";
                }
                ?>
            </select>
        </div>

        <!-- Select Tenant -->
        <div class="form-group hidden" id="tenantSelectWrapper">
            <select name="select_tenant" id="select_tenant" class="form-control">
                <option disabled selected value="">Select Tenant</option>
                <?php
                $result = mysqli_query($conn, "SELECT t_no, tname FROM add_tenant");
                while ($row = mysqli_fetch_assoc($result)) {
                    $tenant_id = $row['t_no'];
                    $tenant_name = $row['tname'];
                    echo "<option value='$tenant_id'>$tenant_name</option>";
                }
                ?>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="submit" class="btn btn-custom">Submit</button>
        </div>
    </form>
</div>


<!-- === Report Dropdown & Submission Logic === -->
<script>
  $(document).ready(function () {
    function showHideFields(reportFile) {
      $("#ownerSelectWrapper, #buildingSelectWrapper, #tenantSelectWrapper").addClass("hidden");
      $("#select_owner, #select_building, #select_tenant").prop("required", false);

      if (["income_by_owner.php", "total_finance_by_owner_report.php"].includes(reportFile)) {
        $("#ownerSelectWrapper").removeClass("hidden");
        $("#select_owner").prop("required", true);
      } else if (["income_by_building.php", "total_finance_by_building_report.php"].includes(reportFile)) {
        $("#buildingSelectWrapper").removeClass("hidden");
        $("#select_building").prop("required", true);
      } else if (["tenant_payment_report.php", "late_tenant_report.php"].includes(reportFile)) {
        $("#tenantSelectWrapper").removeClass("hidden");
        $("#select_tenant").prop("required", true);
      }
    }

    $('#report_type').on('change', function () {
      const selectedFile = $(this).val();
      showHideFields(selectedFile);
    });

    $('#reportForm').on('submit', function (e) {
      e.preventDefault();

      const reportPage = $('#report_type').val();
      let url = reportPage + '?';

      if (reportPage.includes("owner") && $('#select_owner').val()) {
        url += 'select_owner=' + $('#select_owner').val();
      } else if (reportPage.includes("building") && $('#select_building').val()) {
        url += 'select_building=' + $('#select_building').val();
      } else if ((reportPage.includes("tenant") || reportPage.includes("late_tenant")) && $('#select_tenant').val()) {
        url += 'select_tenant=' + $('#select_tenant').val();
      }

      window.location.href = url;
    });
  });
</script>



<!-- ------------------------GENERATE AGREEMENT--------------------------------- -->
<div id="generateAgreement" class="section" style="display:none;">
  <h2>Generate Agreement</h2>
  <form action="generate_agreement.php" method="POST" class="form-group">
    <label for="building">Select Building</label>
    <select name="building" id="building" class="form-control" required>
      <option value="">-- Select Building --</option>
      <?php
        $buildingResult = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");
        while ($row = mysqli_fetch_assoc($buildingResult)) {
            echo "<option value='{$row['b_no']}'>{$row['building_name']}</option>";
        }
      ?>
    </select>

    <label for="unit_no">Select Unit No</label>
    <select name="unit_no" id="unit_no" class="form-control" required>
      <option value="">-- Select Unit No --</option>
      <!-- Populated dynamically via JS -->
    </select>

    <button type="submit" class="btn btn-custom">Generate</button>
  </form>
</div>


<!-- === Dynamic Dropdown for Unit No (Generate Agreement) === -->
<script>
$(document).ready(function () {
  $('#building').on('change', function () {
    var buildingId = $(this).val();

    if (buildingId !== "") {
      $.ajax({
        url: 'fetch_units.php',
        type: 'POST',
        data: { building_id: buildingId },
        success: function (response) {
          $('#unit_no').html(response);
        }
      });
    } else {
      $('#unit_no').html('<option value="">-- Select Unit No --</option>');
    }
  });
});
</script>


    <!-- ------------------------Alerts--------------------------- -->
  
<div id="alerts" class="section" style="display:none;">
          <h2>
           Alerts
       </h2>
          <div class="alert-buttons">
          <!-- <p>Manage tenants, leases, and contact info.</p> -->
          <button class="alert-btn warning-btn" onclick="showAlertSection('lateTenants')">
       ⚠️ Late Tenants
          <?php if ($late_tenant_count > 0): ?>
             <span class="alert-badge-btn"><?= $late_tenant_count; ?></span>
          <?php endif; ?>
          </button>

          <button class="alert-btn warning-btn" onclick="showAlertSection('expiredLeases')">
            📅 Expired Leases
          <?php if ($expired_lease_count > 0): ?>
            <span class="alert-badge-btn"><?= $expired_lease_count; ?></span>
          <?php endif; ?>
         </button>

          </div>

        <!-- Late Tenants Section -->
      
       <div id="lateTenants" style="display:none;">
            <h3>Late Tenant</h3>

       <?php 
           function getRentPeriodRange($startDate, $schedule) {
             $now = new DateTime(); // Today
             $start = new DateTime($startDate); // Original pay_date
             $periodStart = clone $start;

             switch (strtolower($schedule)) {
                 case 'monthly':      $interval = new DateInterval('P1M'); break;
                 case 'quarterly':    $interval = new DateInterval('P3M'); break;
                 case 'half-yearly':  $interval = new DateInterval('P6M'); break;
                 case 'yearly':       $interval = new DateInterval('P1Y'); break;
                 default:             $interval = new DateInterval('P1M');
             }

             // Move forward in intervals until we hit or pass today
             while ($periodStart <= $now) {
                 $periodStart->add($interval);
             }

             // Step back to current period start
             $periodStart->sub($interval);
             $periodEnd = clone $periodStart;
             $periodEnd->add($interval)->sub(new DateInterval('P1D'));

             return [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')];
         }

         $tenants = $conn->query("SELECT * FROM add_tenant");

      ?>
            <table class="table">
                 <tr>
                      <th>Tenant ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Payment</th>
                      <th>Property Name</th>
                      <th>Unit Number</th>
                      <th>Rent Amount</th>
                      <th>Current Date</th>
                      <th>Action</th>
                 </tr>
    <?php
       if ($tenants->num_rows > 0) {
         while ($tenant = $tenants->fetch_assoc()) {
                $payDate = $tenant['pay_date'];
                $schedule = $tenant['pay_schedule'];

                if (!$payDate || !$schedule) continue; // skip if missing

                list($periodStart, $periodEnd) = getRentPeriodRange($payDate, $schedule);

                // Optional ±15 days grace window
                $graceStart = (new DateTime($periodStart))->sub(new DateInterval('P15D'))->format('Y-m-d');
                $graceEnd = (new DateTime($periodEnd))->add(new DateInterval('P15D'))->format('Y-m-d');

                $tname = $conn->real_escape_string($tenant['tname']);
                $building = $conn->real_escape_string($tenant['building_name']);
                $unit = $conn->real_escape_string($tenant['unit_no']);

                // Check for an approved payment in that range
                $paymentSql = "
                    SELECT 1 FROM payments_info
                    WHERE tenant_name = '$tname'
                      AND property_name = '$building'
                      AND unit_no = '$unit'
                      AND approval_status = 'Approved'
                      AND pay_date BETWEEN '$graceStart' AND '$graceEnd'
                    LIMIT 1
                ";

                $hasPaid = $conn->query($paymentSql);

                if ($hasPaid->num_rows === 0) {
                    // Tenant is late
                    $phone = "91" . preg_replace('/[^0-9]/', '', $tenant['phoneno']);
                    $message = "Dear {$tenant['tname']}, your rent of ₹{$tenant['rent_amt']} for {$tenant['building_name']} is overdue. Please pay as soon as possible.";
                    $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);

                    echo "<tr>
                        <td>{$tenant['t_no']}</td>
                        <td>{$tenant['tname']}</td>
                        <td>{$tenant['phoneno']}</td>
                        <td>{$tenant['pay_date']}</td>
                        <td>{$tenant['building_name']}</td>
                        <td>{$tenant['unit_no']}</td>
                        <td>{$tenant['rent_amt']}</td>
                        <td>" . date('Y-m-d') . "</td>
                        <td><a href='{$waLink}' target='_blank' class='btn btn-success'>Send Reminder</a></td>
                    </tr>";
                }
            }
          } else {
             echo "<tr><td colspan='9' style='text-align:center;'>No tenants found.</td></tr>";
          }
        ?>


      </table>   
          <div class="text-center">
                <button class="btn btn-secondary" onclick="goBackToAlerts()">🔙 Back</button>
          </div>   
   </div>   

         <!-- Expired Leases Section -->
    <div id="expiredLeases" style="display:none;">
        <h3>Expired Leases</h3>
        <table class="table">
        <tr>
            <th>Tenant Name</th>
            <th>Building</th>
            <th>Unit No.</th>
            <th>Lease Start</th>
            <th>Lease End</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
<?php
include("db_config.php");
$today = new DateTime();

$leaseQuery = "
    SELECT tname, select_building, unit_no, t_start, t_end_date, phoneno  
    FROM add_tenant
";
$leaseResult = $conn->query($leaseQuery);

if ($leaseResult && $leaseResult->num_rows > 0) {
    while ($row = $leaseResult->fetch_assoc()) {
        $t_start = new DateTime($row['t_start']);
        $t_end_date = new DateTime($row['t_end_date']);

        if ($t_end_date < $today) {
            $fullName = $row['tname'];

            // Get building name
            $building_id = $row['select_building'];
            $building_name = "Unknown";
            $building_result = $conn->query("SELECT building_name FROM building_info WHERE b_no = '$building_id'");
            if ($building_result && $building_result->num_rows > 0) {
                $building_row = $building_result->fetch_assoc();
                $building_name = $building_row['building_name'];
            }

            // Prepare WhatsApp message
            $tenantName = $fullName;
            $phone = "91" . preg_replace('/[^0-9]/', '', $row['phoneno']); // Clean phone number
            $endDate = $t_end_date->format('d/m/Y');
            $message = "Hi {$tenantName}, just a quick reminder that our lease agreement for {$building_name} ended on {$endDate}. Please let me know if you’d like to discuss renewal or the next steps. Thanks!";
            $whatsAppLink = "https://wa.me/{$phone}?text=" . urlencode($message);

            echo "<tr>
                <td>{$fullName}</td>
                <td>{$building_name}</td>
                <td>{$row['unit_no']}</td>
                <td>" . $t_start->format('d/m/Y') . "</td>
                <td>{$endDate}</td>
                <td>{$row['phoneno']}</td>
                <td><a href='{$whatsAppLink}' target='_blank' class='btn btn-success'>Send Reminder</a></td>
            </tr>";
        }
    }
} else {
    echo "<tr><td colspan='7'>No tenants found.</td></tr>";
}
?>

    </table>
    <div class="text-center">
        <button class="btn btn-secondary" onclick="goBackToAlerts()">🔙 Back</button>
    </div>
</div>

              
<!-- === Section Visibility Logic for Alerts === -->
<script>

  function goBackToAlerts() {
    // Hide individual alert subsections
    document.getElementById('lateTenants').style.display = 'none';
    document.getElementById('expiredLeases').style.display = 'none';

    // Show the alert button list again
    const alertButtons = document.querySelector('.alert-buttons');
    if (alertButtons) {
      alertButtons.style.display = 'flex';
    }
  }

  function showAlertSection(id) {
    // Hide the alert buttons
    const alertButtons = document.querySelector('.alert-buttons');
    if (alertButtons) {
      alertButtons.style.display = 'none';
    }

    // Hide all subsections
    var sections = document.querySelectorAll('#alerts > div');
    sections.forEach(section => {
      if (section !== alertButtons) {
        section.style.display = 'none';
      }
    });

    // Show the selected alert subsection
    document.getElementById(id).style.display = 'block';
  }
</script>

        </div>

      </main>
    </div>
  </div>

</body>
</html>