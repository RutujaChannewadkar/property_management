<?php
include("db_config.php");

$buildings = $conn->query("SELECT DISTINCT building_id, building_name FROM unit_info");

$selected_building = $_GET['building'] ?? '';
$selected_year = $_GET['year'] ?? '';

$units = [];
if ($selected_building && $selected_year) {
    $stmt = $conn->prepare("SELECT unit_no FROM unit_info WHERE building_id = ?");
    $stmt->bind_param("i", $selected_building);
    $stmt->execute();
    $result = $stmt->get_result();
    $units = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f7f7;
        padding: 20px;
    }

    form {
        margin-bottom: 30px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    label {
        margin-right: 10px;
        font-weight: bold;
    }

    select {
        margin-right: 20px;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        padding: 6px 15px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 0 8px rgba(0,0,0,0.05);
    }

    th, td {
        text-align: center;
        padding: 12px 8px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007BFF;
        color: white;
        font-weight: normal;
    }

    td.vacant {
        background-color: #fbe9e7;
        color: #d32f2f;
        font-weight: bold;
    }

    td.occupied {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    td small {
        display: block;
        color: #555;
        margin-top: 3px;
    }

    hr {
        border: none;
        border-top: 1px solid #ccc;
        margin: 5px 0;
    }

    .btn-secondary-custom {
       display: inline-block;
       padding: 4px 5px;
       margin-left: 10px;

       background-color: #6c757d;       /* Bootstrap secondary gray */
       color: #fff;
       text-decoration: none;
     /*  font-weight: 400;*/
       border-radius: 5px;
       box-shadow: 0 3px 6px rgba(0,0,0,0.1);
       border: none;
       cursor: pointer;
     }

    .btn-secondary-custom:hover,
    .btn-secondary-custom:focus {
       background-color: #5a6268;       /* Darker shade on hover */
       box-shadow: 0 5px 12px rgba(0,0,0,0.15);
       outline: none;
     }

</style>



<form method="get">
    <label>Select Building:</label>
    <select name="building" required>
        <option value="">-- Select --</option>
        <?php while ($row = $buildings->fetch_assoc()): ?>
            <option value="<?= $row['building_id'] ?>" <?= $selected_building == $row['building_id'] ? 'selected' : '' ?>>
                <?= $row['building_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Select Year:</label>
    <select name="year" required>
        <option value="">-- Select --</option>
        <?php for ($y = date("Y"); $y >= 2000; $y--): ?>
            <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
    </select>

    <button type="submit">Fetch Units</button>
    <?php
          $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
          echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
     ?>

</form>

<?php if (!empty($units)): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Unit No.</th>
            <?php foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month): ?>
                <th><?= $month ?></th>
            <?php endforeach; ?>
        </tr>

        <?php
        $tenantStmt = $conn->prepare("
            SELECT t_no, tname, movein, moveout 
            FROM add_tenant 
            WHERE unit_no = ? 
              AND STR_TO_DATE(movein, '%Y-%m-%d') <= ? 
              AND (
                  moveout IS NULL OR STR_TO_DATE(moveout, '%Y-%m-%d') >= ?
              )
            ORDER BY STR_TO_DATE(movein, '%Y-%m-%d') ASC
        ");

        foreach ($units as $unit):
        ?>
            <tr>
                <td><?= $unit['unit_no'] ?></td>
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $monthStart = date("$selected_year-$m-01");
                    $monthEnd = date("Y-m-t", strtotime($monthStart));

                    $tenantStmt->bind_param("iss", $unit['unit_no'], $monthEnd, $monthStart);
                    $tenantStmt->execute();
                    $tenantResult = $tenantStmt->get_result();

                    if ($tenantResult->num_rows > 0) {
                        $names = [];
                        while ($row = $tenantResult->fetch_assoc()) {
                            $tenant_name = htmlspecialchars($row['tname']);
                            $t_no = $row['t_no']; // Make sure you SELECT this column in the SQL query
                            $names[] = "<a href='tenant_information.php?t_no=$t_no' class='tenant-link'>$tenant_name</a><br><small>(" . $row['movein'] . " to " . $row['moveout'] . ")</small>";
                        }
                     echo "<td  class='occupied' >" . implode("<hr>", $names) . "</td>";
                    } else {
                        echo "<td class='vacant'>Vacant</td>";
                    }
                 }
                ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


