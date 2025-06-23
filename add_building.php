<!DOCTYPE html>
<html>
<head>
    <title>Add Property</title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 70%;
            margin: 60px auto;
        }
        .form-container2
        {
             background: #ffffff;
             border: 1px solid #ccc;
             border-radius: 10px;
             box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
             padding: 8px;
             font-size: 15px;
             margin: 10px auto 0 auto;
             margin-bottom: 3%;

        }

        .form-container h3 {
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            height: 45px;
            font-size: 16px;
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

        .text-center {
            text-align: center;
        }

        .checkbox
        {
            display: inline-block;
            margin-left: 15%;
        }
        
      /* input::placeholder {
    font-family: Arial, sans-serif;
    font-size: 16px;
    font-weight: normal;
    color: #000;
}*/

    </style>
    

    <script type="text/javascript">

        function toggleUnitTypeFields(selectElement, index) {
         const selectedValue = selectElement.value;
         const shopInput = document.getElementById(`shop_no_${index}`);
         const flatInput = document.getElementById(`flat_no_${index}`);

         if (selectedValue === "Shop") {
                shopInput.style.display = "block";
                flatInput.style.display = "none";
                flatInput.value = ""; // Clear hidden field
          } else if (selectedValue === "Flat") {
                flatInput.style.display = "block";
                shopInput.style.display = "none";
                shopInput.value = ""; // Clear hidden field
          } else {
                shopInput.style.display = "none";
                flatInput.style.display = "none";
                shopInput.value = "";
                flatInput.value = "";
          }
       }

    

    function generateUnitFields(count) {
    const container = document.getElementById("unit-details-container");
    container.innerHTML = ""; // Clear previous entries

    count = parseInt(count);
    if (isNaN(count) || count <= 0) return;

    const table = document.createElement("table");
    table.className = "table table-bordered";

    // Create table header
    const thead = document.createElement("thead");
    thead.innerHTML = `
        <tr>
            <th>Unit No</th>
            <th>Type   </th>
            <th>Shop / Flat</th>
            
            <th>Floor</th>
            <th>Area (sq.ft)</th>
            <th>Fixed Rent</th>
            <th>Fixed Deposit</th>
            <th>Maintenance Fees</th
        </tr>
    `;
    table.appendChild(thead);

    const tbody = document.createElement("tbody");

    for (let i = 0; i < count; i++) {
     const row = document.createElement("tr");

            row.innerHTML = `
    <td><input type="text" name="unit_no[]" class="form-control" placeholder="Unit No"></td>
    <td>
        <select name="unit_area[]" class="form-control">
            <option value="">Select Type</option>
            <option value="Residential">Residential</option>
            <option value="Industrial">Industrial</option>
            <option value="Commercial">Commercial</option>
            <option value="Urban">Urban</option>
        </select>
    </td>
    <td>
        <select name="unit_type[]" class="form-control" onchange="toggleUnitTypeFields(this, ${i})">
            <option value="">Select</option>
            <option value="Shop">Shop</option>
            <option value="Flat">Flat</option>
            <option value="Shed">Shed</option>

        </select>
    </td>

    <td><input type="text" name="floor[]" class="form-control" placeholder="Floor"></td>
    <td><input type="text" name="area_sqft[]" class="form-control" placeholder="Area (sq.ft)"></td>
    <td><input type="text" name="fixed_rent[]" class="form-control" placeholder="Fixed Rent"></td>
    <td><input type="text" name="fixed_deposit[]" class="form-control" placeholder="Fixed Deposit"></td>
    <td><input type="text" name="maintenance_fee[]" class="form-control" placeholder="Maintenance"></td>

`;


        tbody.appendChild(row);
    }

    table.appendChild(tbody);
    container.appendChild(table);
}

function validate() {
    const form = document.forms['f1'];

    // Property name
    if (form.building_name.value.trim() === "") {
        alert("Please enter the Property Name.");
        form.building_name.focus();
        return false;
    }

    // Address
    if (form.address.value.trim() === "") {
        alert("Please enter the Address.");
        form.address.focus();
        return false;
    }

    // Owner
    if (form.select_owner.value.trim() === "") {
        alert("Please select an Owner.");
        form.select_owner.focus();
        return false;
    }

    // Number of Units
    const noOfUnits = form.noofunit.value.trim();
    if (noOfUnits === "" || isNaN(noOfUnits) || parseInt(noOfUnits) <= 0) {
        alert("Please enter a valid Number of Units.");
        form.noofunit.focus();
        return false;
    }

    // Unit validations (only if fields are generated)
    const unitNos = document.getElementsByName("unit_no[]");
    const unitAreas = document.getElementsByName("unit_area[]");
    const unitTypes = document.getElementsByName("unit_type[]");
    const floors = document.getElementsByName("floor[]");
    const areas = document.getElementsByName("area_sqft[]");
    const rents = document.getElementsByName("fixed_rent[]");
    const deposits = document.getElementsByName("fixed_deposit[]");
    const maintenanceFees = document.getElementsByName("maintenance_fee[]");

    for (let i = 0; i < unitNos.length; i++) {
        if (unitNos[i].value.trim() === "") {
            alert(`Please enter Unit No for row ${i + 1}.`);
            unitNos[i].focus();
            return false;
        }

        if (unitAreas[i].value.trim() === "") {
            alert(`Please select Unit Area for row ${i + 1}.`);
            unitAreas[i].focus();
            return false;
        }

        if (unitTypes[i].value.trim() === "") {
            alert(`Please select Unit Type for row ${i + 1}.`);
            unitTypes[i].focus();
            return false;
        }

        if (floors[i].value.trim() === "") {
            alert(`Please enter Floor for row ${i + 1}.`);
            floors[i].focus();
            return false;
        }

        if (areas[i].value.trim() === "" || isNaN(areas[i].value)) {
            alert(`Please enter valid Area for row ${i + 1}.`);
            areas[i].focus();
            return false;
        }

        if (rents[i].value.trim() === "" || isNaN(rents[i].value)) {
            alert(`Please enter valid Rent Amount for row ${i + 1}.`);
            rents[i].focus();
            return false;
        }

        if (deposits[i].value.trim() === "" || isNaN(deposits[i].value)) {
            alert(`Please enter valid Deposit Amount for row ${i + 1}.`);
            deposits[i].focus();
            return false;
        }

        if (maintenanceFees[i].value.trim() === "" || isNaN(maintenanceFees[i].value)) {
            alert(`Please enter valid Maintenance Fee for row ${i + 1}.`);
            maintenanceFees[i].focus();
            return false;
        }
    }

    return true; // Submit the form if all validations pass
}


</script>


</head>
<body>

    <div class="form-container">
        <form name="f1" method="post">
            <h3 class="text-center">Add Property</h3>

            <div class="form-group">
                <input type="text" name="building_name" placeholder="Property Name" class="form-control">
            </div>

            <div class="form-group">
                <input type="text" name="address" placeholder="Address" class="form-control">
            </div>


            <div class="form-group">
                <select name="select_owner" class="form-control">
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


            <div class="form-group">
                <!-- <input type="number" name="noofunit" placeholder="No. of Units" class="form-control"> -->
                <input type="number" name="noofunit" placeholder="No. of Units" class="form-control" onchange="generateUnitFields(this.value)">
            </div>

            <div id="unit-details-container"></div>


            <div class="form-group">
                <input type="text" name="note" placeholder="Note" class="form-control">
            </div>

            <div class="form-group text-center">
                <!-- <a href="dashboard.php" class="btn btn-secondary-custom">Go back</a> -->
                <?php
                 $section = isset($_GET['section']) ? $_GET['section'] : 'build';
                echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
                 ?>

                <button type="submit" name="submit" class="btn btn-custom" onclick="return validate()">Submit</button>
                <button type="reset" class="btn btn-secondary-custom">Reset</button>
            </div>
        </form>
<?php
if (isset($_POST["submit"])) {
    include("db_config.php");

    $building_name = $_POST["building_name"];
    $address = $_POST["address"];
    $select_owner = $_POST["select_owner"];
    $noofunit=$_POST['noofunit'];
   
    $note = $_POST["note"];

    // Insert into building_info (no unit_nos or unit_area here!)
    $sql = "INSERT INTO building_info(building_name, address, select_owner,noofunit, note) 
            VALUES ('$building_name', '$address', '$select_owner','$noofunit', '$note')";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='row text-center'>Building Data Inserted...</div>";

        // Get the ID of the inserted building
        $building_id = mysqli_insert_id($conn);

        // Insert units if present
        if (!empty($_POST['unit_no']) && !empty($_POST['unit_area'])) {
            $unit_nos = $_POST["unit_no"];
            $unit_areas = $_POST["unit_area"];
            $unit_type = $_POST["unit_type"];
            
            $floors = $_POST["floor"];
            $areas = $_POST["area_sqft"];
            $fixed_rents = $_POST["fixed_rent"];
            $fixed_deposits = $_POST["fixed_deposit"];
            $maintenance_fee=$_POST["maintenance_fee"];


            for ($i = 0; $i < count($unit_nos); $i++) {
                $unit_no = mysqli_real_escape_string($conn, $unit_nos[$i]);
                $unit_area = mysqli_real_escape_string($conn, $unit_areas[$i]);

                $unit_types = $_POST["unit_type"]; // array
                $unit_type_i = mysqli_real_escape_string($conn, $unit_types[$i]); // GOOD

                     
                $floor = mysqli_real_escape_string($conn, $floors[$i]);
                $area_sqft = mysqli_real_escape_string($conn, $areas[$i]);
                $fixed_rent = mysqli_real_escape_string($conn, $fixed_rents[$i]);
                $fixed_deposit = mysqli_real_escape_string($conn, $fixed_deposits[$i]);
                $maintenance_fee = mysqli_real_escape_string($conn, $maintenance_fee[$i]);
                      


            if (!empty($unit_no) && !empty($unit_area)) {
               $unit_sql = "INSERT INTO unit_info(
                        building_id, building_name, unit_no, unit_area,
                        unit_type, floor, area_sqft, rent_amt, deposit_amt,maintenance_fee
                     ) 
                     VALUES (
                        '$building_id', '$building_name', '$unit_no', '$unit_area', '$unit_type_i', '$floor', '$area_sqft', '$fixed_rent', '$fixed_deposit','$maintenance_fee'
                     )";
        mysqli_query($conn, $unit_sql);
     }
  }
       echo "<div class='row text-center'>Units Data Inserted...</div>";
        }
    } else {
        echo "<div class='row text-center'>Building Data NOT Inserted...</div>";
    }
}
?>


    </div>

</body>
</html>
