<?php
include("db_config.php");

if (isset($_POST['building'])) {
    $building = mysqli_real_escape_string($conn, $_POST['building']);

    $query = "SELECT unit_no FROM unit_info WHERE building_name = '$building'";
    $result = mysqli_query($conn, $query);

    echo '<option disabled selected value="">Select a unit</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['unit_no']}'>{$row['unit_no']}</option>";
    }
}
?>
