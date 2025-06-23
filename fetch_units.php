<?php
include("db_config.php");

if (isset($_POST['building_id'])) {
    $buildingId = $_POST['building_id'];

    // Updated query to also fetch rent and deposit amounts
    // $query = "SELECT unit_no, unit_area, rent_amt, deposit_amt FROM unit_info WHERE building_id = '$buildingId'";
    $query = "SELECT unit_no, unit_area, unit_type, rent_amt, deposit_amt FROM unit_info WHERE building_id = '$buildingId'";

    $result = mysqli_query($conn, $query);

    echo "<option value=''>-- Select Unit No --</option>";
    while ($row = mysqli_fetch_assoc($result)) {
    $unit_no = htmlspecialchars($row['unit_no']);
    $unit_area = htmlspecialchars($row['unit_area']);
    $unit_type = htmlspecialchars($row['unit_type']); // newly added
    $rent_amt = htmlspecialchars($row['rent_amt']);
    $deposit_amt = htmlspecialchars($row['deposit_amt']);

    echo "<option value='{$unit_no}' 
             data-unit-area='{$unit_area}' 
             data-unit-type='{$unit_type}' 
             data-rent='{$rent_amt}' 
             data-deposit='{$deposit_amt}'>
            {$unit_no}
          </option>";
}

}


?>

