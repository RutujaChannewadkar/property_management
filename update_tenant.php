<?php
include("db_config.php");

// Sanitize and validate tenant ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) die("Invalid tenant ID.");

// Fetch existing tenant data
$stmt = $conn->prepare("SELECT * FROM add_tenant WHERE t_no = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) die("Tenant not found.");
$row = $result->fetch_assoc();

$feedback = '';
if (isset($_POST['submit_reg'])) {
    // Gather & sanitize form data
    $building_id     = $_POST['select_building'];
    $building_name   = $_POST['building_name'];
    $unit_no         = $_POST['unit_no'];
    $tname           = $_POST['tname'];
    $username        = $_POST['username'];
    $tenant_address  = $_POST['tenant_address'];
    $email           = $_POST['email'];
    $password        = $_POST['password'];
    $phoneno         = $_POST['phoneno'];
    $age             = $_POST['age'];
    $pan_no          = $_POST['pan_no'];
    $aadhar_no       = $_POST['aadhar_no'];
    $identi          = $_POST['identi'];
    $rent_amt        = $_POST['rent_amt'];
    $deposit_amt     = $_POST['diposit'];
    $movein          = $_POST['movein'];
    $moveout         = $_POST['moveout'];
    $deposit_date    = $_POST['diposit_date'];
    $pay_schedule    = $_POST['pay_schedule'];
    $pay_date        = $_POST['pay_date'];
    $t_start         = $_POST['t_start'];
    $t_end           = $_POST['t_end'];
    $lease_period    = $_POST['lease_period'];
    $t_end_date      = $_POST['t_end_date'];
    $police          = $_POST['police'];
    $emg_name        = $_POST['emg_name'];
    $relation        = $_POST['relation'];
    $emg_phone       = $_POST['emg_phone'];
    $emg_add         = $_POST['emg_add'];
    $company_name       = $_POST['company_name']     ?? '';
    $partnership_type   = $_POST['partnership_type'] ?? '';
    $company_address    = $_POST['company_address']  ?? '';
    $partner_name       = $_POST['partner_name']     ?? '';
    $partner_address    = $_POST['partner_address']  ?? '';
    $partner_phone      = $_POST['partner_phone']    ?? '';
    $partner_email      = $_POST['partner_email']    ?? '';

    // Handle file uploads
    $fileupload_path  = $row['fileupload'];
    if (!empty($_FILES['fileupload']['tmp_name']) && $_FILES['fileupload']['error'] === 0) {
        $f = basename($_FILES['fileupload']['name']);
        $fileupload_path = "uploads/" . time() . "_{$f}";
        move_uploaded_file($_FILES['fileupload']['tmp_name'], $fileupload_path);
    }

    $agreeupload_path = $row['agree_upload'];
    if (!empty($_FILES['agree_upload']['tmp_name']) && $_FILES['agree_upload']['error'] === 0) {
        $f2 = basename($_FILES['agree_upload']['name']);
        $agreeupload_path = "uploads/" . time() . "_{$f2}";
        move_uploaded_file($_FILES['agree_upload']['tmp_name'], $agreeupload_path);
    }

    // Prepare and execute UPDATE query
    $sql = "
        UPDATE add_tenant SET
            select_building=?, building_name=?, unit_no=?,
            tname=?, username=?, tenant_address=?, email=?, password=?, phoneno=?, age=?, pan_no=?, aadhar_no=?,
            identi=?, fileupload=?, agree_upload=?, rent_amt=?, diposit=?, movein=?, moveout=?, diposit_date=?, pay_schedule=?, pay_date=?,
            t_start=?, t_end=?, lease_period=?, t_end_date=?, police=?, emg_name=?, relation=?, emg_phone=?, emg_add=?,
            company_name=?, partnership_type=?, company_address=?, partner_name=?, partner_address=?, partner_phone=?, partner_email=?
        WHERE t_no=?
    ";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param(
        "iisissssssisisssssssissssssssssssssssi",
        $building_id, $building_name, $unit_no,
        $tname, $username, $tenant_address, $email, $password, $phoneno, $age, $pan_no, $aadhar_no,
        $identi, $fileupload_path, $agreeupload_path, $rent_amt, $deposit_amt, $movein,
        $moveout, $deposit_date, $pay_schedule, $pay_date, $t_start, $t_end, $lease_period,
        $t_end_date, $police, $emg_name, $relation, $emg_phone, $emg_add,
        $company_name, $partnership_type, $company_address, $partner_name, $partner_address,
        $partner_phone, $partner_email, $id
    );
    if ($stmt2->execute()) {
        $feedback = "<div class='alert alert-success'>Tenant updated successfully.</div>";
    } else {
        $feedback = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt2->error) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Tenant</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style type="text/css">
        .brandname
      {
/*           background-color:deeppink;*/
/*           color:#000123;*/
           padding: 10px;
/*           color:white;*/
      }
     .form{
              
              padding: 10px;
/*              background: linear-gradient(to bottom right,deeppink,purple);*/
/*              color:white;*/
              
              font-size: 18px;
         }
         /*label{
              float: right;
         }*/
         .row{
             margin-bottom:10px ;
         }

         .btn{
/*            background-color: gold;*/
              border: none;
              font-size: 20px;
              padding: 7px;
              width: 16%;
/*            color:#000123;*/
         }
         .btn:hover{
/*            background-color: #000123;*/
/*            color:white;*/
         }
        .cardcontainer
         {
/*               background-color: gold;*/
/*                box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);*/
                transition: 0.3s;
                text-align: center;
                border: 3px solid white;
/*                color:#000123;*/
                border-radius: 7px;

         }
         .cardcontainer:hover
         {
/*                box-shadow: 0px 10px 5px 0px rgba(0, 0, 0, 0.5);*/
                transition: 0.3s;
         }
         .cardData
         {
/*          background-color: limegreen;*/
/*          color:white;*/
          width: 100%;
          padding: 10px;
         

         }
       a{
         text-decoration: none;
       }

       h4{
        text-align: center;
       }

       .radio-inline
       {
           padding-left: -10%;
       }

       .small-upload {
            font-size: 12px;
            padding: 4px 6px;
            height: auto;
            width: 70%;
       }
       .notes
       {
        font-size: 12px;
        font-weight: bold;
       }

    </style>

    <script>
    function showDateTime() {
        const d = new Date();
        document.querySelector('input[name="reg_date"]').value = 
            d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();
        document.querySelector('input[name="reg_time"]').value = d.toLocaleTimeString();
    }
    function updateBuildingName() {
        const select = document.getElementById("select_building");
        const name   = select.options[select.selectedIndex].text;
        document.getElementById("building_name").value = name;
    }

    $(document).ready(function() {
        $('#select_building').on('change', function() {
          $.post("fetch_units.php", {building_id: this.value}, function(res) {
              $('#unit_no_dropdown').html(res).trigger('change');
          });
        });

        $(document).on('change', '#unit_no_dropdown', function() {
            const opt = $(this).find('option:selected');
            $('#rent_amt').val(opt.data('rent') || '');
            $('#diposit_amt').val(opt.data('deposit') || '');
            const type = (opt.data('unit-type') || '').toLowerCase();
            if (type === 'shop' || type === 'shed') {
                $('#industrial-section').show();
            } else {
                $('#industrial-section, #partner-info-section').hide();
            }
        });

        $(document).on('change', 'input[name="partnership_type"]', function() {
            if (this.value === "Partnership") {
                $('#partner-info-section').show();
            } else {
                $('#partner-info-section').hide();
            }
        });

        // Lease date calc
        const tStart = $('input[name="t_start"]'),
              tEnd   = $('input[name="t_end"]'),
              lperiod= $('select[name="lease_period"]'),
              tendDate = $('input[name="t_end_date"]');

        function calculateEnd() {
            const start = new Date(tStart.val());
            const n = parseInt(tEnd.val());
            const period = lperiod.val();
            if (!start || isNaN(n)) { tendDate.val(''); return; }
            const end = new Date(start);
            if (period === 'Year') end.setFullYear(end.getFullYear()+n);
            if (period === 'Month') end.setMonth(end.getMonth()+n);
            if (period === 'Week') end.setDate(end.getDate()+7*n);
            tendDate.val(end.toISOString().split('T')[0]);
        }
        tStart.add(tEnd).add(lperiod).on('change input', calculateEnd);

        // File type validation
        $('form').on('submit', function(e) {
            const allowed = ['application/pdf','image/jpeg','image/png'];
            [ 'fileupload', 'agree_upload' ].forEach(id => {
                const f = document.querySelector(`input[name="${id}"]`).files[0];
                if (f && !allowed.includes(f.type)) {
                    alert("Invalid file type for " + id);
                    e.preventDefault();
                }
            });
        });
    });
    </script>
</head>

<body onload="showDateTime()">
<div class="container">
    <?php echo $feedback; ?>
    <form method="post" enctype="multipart/form-data">
      <div class="form">
        <h2 class="text-center">Update Tenant</h2><hr>

        <!-- Property & Unit -->
        <div class="row">
            <div class="col-sm-2">Select Property</div>
            <div class="col-sm-4">
              <select id="select_building" name="select_building" class="form-control" onchange="updateBuildingName()">
                <option disabled value="">Select Property</option>
                <?php
                $resB = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");
                while ($b = mysqli_fetch_assoc($resB)) {
                    $sel = $b['b_no'] == $row['select_building'] ? 'selected' : '';
                    echo "<option value='{$b['b_no']}' $sel>{$b['building_name']}</option>";
                }
                ?>
              </select>
              <input type="hidden" id="building_name" name="building_name" value="<?= htmlspecialchars($row['building_name']) ?>">
            </div>
            <div class="col-sm-2">Unit No.</div>
            <div class="col-sm-4">
              <select name="unit_no" id="unit_no_dropdown" class="form-control">
                <option><?= htmlspecialchars($row['unit_no']) ?></option>
              </select>
            </div>
        </div><hr>

        <!-- Tenant Info -->
        <h4>Tenant Information</h4>
        <div class="row">
          <div class="col-sm-2">Date</div>
          <div class="col-sm-4"><input name="reg_date" class="form-control" readonly value="<?= htmlspecialchars($row['reg_date']) ?>"></div>
          <div class="col-sm-2">Time</div>
          <div class="col-sm-4"><input name="reg_time" class="form-control" readonly value="<?= htmlspecialchars($row['reg_time']) ?>"></div>
        </div>

        <div class="row">
          <div class="col-sm-2">Full Name</div>
          <div class="col-sm-4"><input name="tname" value="<?= htmlspecialchars($row['tname']) ?>" class="form-control"></div>
          <div class="col-sm-2">Username</div>
          <div class="col-sm-4"><input name="username" value="<?= htmlspecialchars($row['username']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Address</div>
          <div class="col-sm-10"><input name="tenant_address" value="<?= htmlspecialchars($row['tenant_address']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Email</div>
          <div class="col-sm-4"><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control"></div>
          <div class="col-sm-2">Password</div>
          <div class="col-sm-4"><input name="password" value="<?= htmlspecialchars($row['password']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Phone</div><div class="col-sm-4"><input name="phoneno" value="<?= htmlspecialchars($row['phoneno']) ?>" class="form-control"></div>
          <div class="col-sm-2">Age</div><div class="col-sm-4"><input name="age" value="<?= htmlspecialchars($row['age']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">PAN</div><div class="col-sm-4"><input name="pan_no" value="<?= htmlspecialchars($row['pan_no']) ?>" class="form-control"></div>
          <div class="col-sm-2">Aadhar</div><div class="col-sm-4"><input name="aadhar_no" value="<?= htmlspecialchars($row['aadhar_no']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">ID Type</div><div class="col-sm-4">
            <select name="identi" class="form-control">
              <?php
                $types = ["Adhar_card"=>"Adhar card","Passport"=>"Passport","Driving_licience"=>"Driving licence"];
                foreach ($types as $v => $l) {
                    $sel = $row['identi']==$v?'selected':'';
                    echo "<option value='$v' $sel>$l</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-2">Upload ID</div><div class="col-sm-4"><input type="file" name="fileupload" accept=".pdf,.jpg,.jpeg,.png"></div>
        </div>

        <!-- Industrial Section -->
        <div id="industrial-section" style="display:none;">
          <hr><h4>Industrial Unit Details</h4>
          <div class="row">
            <div class="col-sm-2">Company Name</div><div class="col-sm-4"><input name="company_name" value="<?= htmlspecialchars($row['company_name']) ?>" class="form-control"></div>
            <div class="col-sm-2">Ownership</div><div class="col-sm-4">
              <label class="radio-inline"><input type="radio" name="partnership_type" value="Sole Proprietorship" <?= $row['partnership_type']=='Sole Proprietorship'?'checked':'' ?>> Sole Proprietorship</label>
              <label class="radio-inline"><input type="radio" name="partnership_type" value="Partnership" <?= $row['partnership_type']=='Partnership'?'checked':'' ?>> Partnership</label>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">Company Address</div><div class="col-sm-10"><input name="company_address" value="<?= htmlspecialchars($row['company_address']) ?>" class="form-control"></div>
          </div>
        </div>

        <!-- Partner Section -->
        <div id="partner-info-section" style="display:none;">
          <hr><h4>Partner Information</h4>
          <div class="row">
            <div class="col-sm-2">Partner Name</div><div class="col-sm-4"><input name="partner_name" value="<?= htmlspecialchars($row['partner_name']) ?>" class="form-control"></div>
            <div class="col-sm-2">Address</div><div class="col-sm-4"><input name="partner_address" value="<?= htmlspecialchars($row['partner_address']) ?>" class="form-control"></div>
          </div>
          <div class="row">
            <div class="col-sm-2">Phone</div><div class="col-sm-4"><input name="partner_phone" value="<?= htmlspecialchars($row['partner_phone']) ?>" class="form-control"></div>
            <div class="col-sm-2">Email</div><div class="col-sm-4"><input name="partner_email" value="<?= htmlspecialchars($row['partner_email']) ?>" class="form-control"></div>
          </div>
        </div>

        <!-- Rent Section -->
        <hr><h4>Rent Information</h4>
        <p class="notes">Note: The fixed Rent and Deposit amount is specified per unit.</p>

        <div class="row">
          <div class="col-sm-2">Rent Amount</div>
          <div class="col-sm-4"><input name="rent_amt" id="rent_amt" value="<?= htmlspecialchars($row['rent_amt']) ?>" class="form-control"></div>
          <div class="col-sm-2">Deposit Amount</div>
          <div class="col-sm-4"><input name="diposit" id="diposit_amt" value="<?= htmlspecialchars($row['diposit']) ?>" class="form-control"></div>
        </div>

        <!-- Move-In/Out & Lease -->
        <div class="row">
          <div class="col-sm-2">Move-in Date</div>
          <div class="col-sm-4"><input type="date" name="movein" value="<?= htmlspecialchars($row['movein']) ?>" class="form-control"></div>
          <div class="col-sm-2">Deposit Date</div>
          <div class="col-sm-4"><input type="date" name="diposit_date" value="<?= htmlspecialchars($row['diposit_date']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Move-out Date</div>
          <div class="col-sm-4"><input type="date" name="moveout" value="<?= htmlspecialchars($row['moveout']) ?>" class="form-control"></div>
          <div class="col-sm-2">Payment Date</div>
          <div class="col-sm-4"><input type="date" name="pay_date" value="<?= htmlspecialchars($row['pay_date']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Lease Term Start</div>
          <div class="col-sm-4"><input type="date" name="t_start" value="<?= htmlspecialchars($row['t_start']) ?>" class="form-control"></div>
          <!-- <div class="col-sm-2"><input type="file" name="agree_upload" accept=".pdf,.jpg,.jpeg,.png"></div> -->
          <div class="col-sm-2">Lease Period</div>
          <div class="col-sm-2"><input name="t_end" value="<?= htmlspecialchars($row['t_end']) ?>" class="form-control"></div>
          <div class="col-sm-2">
            <select name="lease_period" class="form-control">
              <option value="Year" <?= $row['lease_period']=='Year'?'selected':'' ?>>Years</option>
              <option value="Month" <?= $row['lease_period']=='Month'?'selected':'' ?>>Months</option>
              <option value="Week" <?= $row['lease_period']=='Week'?'selected':'' ?>>Weeks</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">Lease Term End</div>
          <div class="col-sm-4"><input name="t_end_date" value="<?= htmlspecialchars($row['t_end_date']) ?>" readonly class="form-control"></div>
          <div class="col-sm-2">Payment Schedule</div>
          <div class="col-sm-4"><select name="pay_schedule" class="form-control">
            <option <?= $row['pay_schedule']=='Weekly'?'selected':'' ?>>Weekly</option>
            <option <?= $row['pay_schedule']=='Monthly'?'selected':'' ?>>Monthly</option>
            <option <?= $row['pay_schedule']=='Annually'?'selected':'' ?>>Annually</option>
          </select>
         </div> 
        </div>

        <div class="row">
            <div class="col-sm-2">Police Verification</div>
          <div class="col-sm-4"><select name="police" class="form-control">
            <option <?= $row['police']=='Verified'?'selected':'' ?>>Verified</option>
            <option <?= $row['police']=='Not-Verified'?'selected':'' ?>>Not-Verified</option>
          </select></div>
        </div>

        <!-- Emergency Contact -->
        <hr><h4>Emergency Contact</h4>
        <div class="row">
          <div class="col-sm-2">Name</div><div class="col-sm-4"><input name="emg_name" value="<?= htmlspecialchars($row['emg_name']) ?>" class="form-control"></div>
          <div class="col-sm-2">Relationship</div><div class="col-sm-4"><input name="relation" value="<?= htmlspecialchars($row['relation']) ?>" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-sm-2">Phone</div><div class="col-sm-4"><input name="emg_phone" value="<?= htmlspecialchars($row['emg_phone']) ?>" class="form-control"></div>
          <div class="col-sm-2">Address</div><div class="col-sm-4"><input name="emg_add" value="<?= htmlspecialchars($row['emg_add']) ?>" class="form-control"></div>
        </div>

        <!-- Action Buttons -->
        <div class="row text-center">
          <a href="dashboard.php#tenant" class="btn btn-default">Go Back</a>
          <button type="submit" name="submit_reg" class="btn btn-success">Save Changes</button>
          <button type="reset" class="btn btn-warning">Reset</button>
        </div>

      </div>
    </form>
</div>
</body>
</html>
