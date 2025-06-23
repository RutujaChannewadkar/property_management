<?php
include("db_config.php");

if (isset($_GET['building'])) {
    $building = mysqli_real_escape_string($conn, $_GET['building']);
    $query = "SELECT unit_no FROM add_tenant WHERE building_name = '$building'";
    $result = mysqli_query($conn, $query);

    echo "<option disabled selected value=''>Select Unit</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $unit = $row['unit_no'];
        echo "<option value='$unit'>$unit</option>";
    }
}
?>
