<?php
include("db_config.php");

// Ensure building ID is provided
if (!isset($_GET['id'])) {
    die("Building ID is required.");
}
$b_no = intval($_GET['id']);

// Fetch building info
$buildingRes = mysqli_query($conn, "SELECT * FROM building_info WHERE b_no = $b_no");
$building = mysqli_fetch_assoc($buildingRes);

// Fetch existing units
$unitsRes = mysqli_query($conn, "SELECT * FROM unit_info WHERE building_id = $b_no");
$units = mysqli_fetch_all($unitsRes, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Property</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 90%;
            margin: 60px auto;
        }
        .form-container h3 {
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 5px;
            transition: background 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-secondary-custom {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 5px;
        }
        .text-center { text-align: center; }
    </style>
</head>
<body>
<div class="form-container">
    <form name="f1" method="post">
        <h3 class="text-center">Update Property</h3>

        <input type="hidden" name="b_no" value="<?= $b_no ?>">

        <div class="form-group">
            <input type="text" name="building_name" class="form-control" placeholder="Property Name" value="<?= htmlspecialchars($building['building_name']) ?>">
        </div>

        <div class="form-group">
            <input type="text" name="address" class="form-control" placeholder="Address" value="<?= htmlspecialchars($building['address']) ?>">
        </div>

        <div class="form-group">
            <select name="select_owner" class="form-control">
                <option value="">Select Owner</option>
                <?php 
                $res = mysqli_query($conn, "SELECT owner_id,oname FROM owner_info");
                while ($o = mysqli_fetch_assoc($res)) {
                    $sel = $o['owner_id'] == $building['select_owner'] ? 'selected' : '';
                    echo "<option value='{$o['owner_id']}' $sel>{$o['oname']}</option>";
                } ?>
            </select>
        </div>

        <div class="form-group">
            <input type="number" name="noofunit" class="form-control" placeholder="Number of Units" value="<?= intval($building['noofunit']) ?>">
        </div>

        <h4>Units Information</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Unit No</th>
                    <th>Unit Area</th>
                    <th>Unit Type</th>
                    <th>Floor</th>
                    <th>Area (sqft)</th>
                    <th>Fixed Rent</th>
                    <th>Deposit</th>
                    <th>Maintenance Fee</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $unitCount = intval($building['noofunit']);
            for ($i = 0; $i < $unitCount; $i++):
                $u = $units[$i] ?? ['unit_no'=>'','unit_area'=>'','unit_type'=>'','floor'=>'','area_sqft'=>'','rent_amt'=>'','deposit_amt'=>'','maintenance_fee'=>''];
            ?>
                <tr>
                    <td><input type="text" name="unit_no[]" class="form-control" value="<?= htmlspecialchars($u['unit_no']) ?>"></td>
                    <td>
                        <select name="unit_area[]" class="form-control">
                            <option value="">Select</option>
                            <?php foreach (['Residential','Industrial','Commercial','Urban'] as $opt): ?>
                                <option <?= $u['unit_area'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="unit_type[]" class="form-control">
                            <option value="">--</option>
                            <?php foreach (['Shop','Flat','Shed'] as $type): ?>
                                <option value="<?= $type ?>" <?= $u['unit_type'] == $type ? 'selected' : '' ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" name="floor[]" class="form-control" value="<?= htmlspecialchars($u['floor']) ?>"></td>
                    <td><input type="text" name="area_sqft[]" class="form-control" value="<?= htmlspecialchars($u['area_sqft']) ?>"></td>
                    <td><input type="text" name="fixed_rent[]" class="form-control" value="<?= htmlspecialchars($u['rent_amt']) ?>"></td>
                    <td><input type="text" name="fixed_deposit[]" class="form-control" value="<?= htmlspecialchars($u['deposit_amt']) ?>"></td>
                    <td><input type="text" name="maintenance_fee[]" class="form-control" value="<?= htmlspecialchars($u['maintenance_fee']) ?>"></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <div class="form-group">
            <input type="text" name="note" class="form-control" placeholder="Note" value="<?= htmlspecialchars($building['note']) ?>">
        </div>

        <div class="text-center">
            <a href="dashboard.php#build" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" name="submit" class="btn btn-custom">Update</button>
        </div>
    </form>

<?php
if (isset($_POST['submit'])) {
    $bn = mysqli_real_escape_string($conn, $_POST['building_name']);
    $addr = mysqli_real_escape_string($conn, $_POST['address']);
    $own = intval($_POST['select_owner']);
    $nof = intval($_POST['noofunit']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    // Update building
    mysqli_query($conn, "UPDATE building_info SET building_name='$bn',address='$addr',select_owner='$own',noofunit='$nof',note='$note' WHERE b_no=$b_no");

    // Delete old units
    mysqli_query($conn, "DELETE FROM unit_info WHERE building_id = $b_no");

    // Insert updated units
    if (!empty($_POST['unit_no'])) {
        for ($i = 0; $i < count($_POST['unit_no']); $i++) {
            $u_no = mysqli_real_escape_string($conn, $_POST['unit_no'][$i]);
            $u_ar = mysqli_real_escape_string($conn, $_POST['unit_area'][$i]);
            $u_ty = mysqli_real_escape_string($conn, $_POST['unit_type'][$i]);
            $fl = mysqli_real_escape_string($conn, $_POST['floor'][$i]);
            $sq = mysqli_real_escape_string($conn, $_POST['area_sqft'][$i]);
            $re = mysqli_real_escape_string($conn, $_POST['fixed_rent'][$i]);
            $de = mysqli_real_escape_string($conn, $_POST['fixed_deposit'][$i]);
            $mf = mysqli_real_escape_string($conn, $_POST['maintenance_fee'][$i]);

            if ($u_no && $u_ar) {
                mysqli_query($conn,
                    "INSERT INTO unit_info (building_id, building_name, unit_no, unit_area, unit_type, floor, area_sqft, rent_amt, deposit_amt, maintenance_fee)
                     VALUES ($b_no,'$bn','$u_no','$u_ar','$u_ty','$fl','$sq','$re','$de','$mf')");
            }
        }
    }

    echo "<div class='text-center text-success'>Building & units updated!</div>";
    echo "<script>setTimeout(()=>location='dashboard.php#build',2000)</script>";
}
?>
</div>
</body>
</html>
