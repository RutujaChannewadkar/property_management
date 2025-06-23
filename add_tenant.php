<!DOCTYPE html>
<html>
<head>   
    <title>Add Tenant</title>

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

<script type="text/javascript">

   function validate() {
    var form = document.f1;

    // Name validation
    var nameRegex = /^[A-Za-z\s]+$/;
    if (form.tname.value.trim() === "") {
        alert("Please enter Tenant Name.");
        form.tname.focus();
        return false;
    }
    if (!nameRegex.test(form.tname.value.trim())) {
        alert("Tenant name must contain only alphabets.");
        form.tname.focus();
        return false;
    }

    // Username
    if (form.username.value.trim() === "") {
        alert("Please enter Username.");
        form.username.focus();
        return false;
    }

    // Email
    if (form.email.value.trim() === "") {
        alert("Please enter Email.");
        form.email.focus();
        return false;
    }

    var emailRegex = /^\S+@\S+\.\S+$/;
    if (!emailRegex.test(form.email.value.trim())) {
        alert("Please enter a valid Email.");
        form.email.focus();
        return false;
    }

    // Password
    if (form.password.value.trim() === "") {
        alert("Please enter a Password.");
        form.password.focus();
        return false;
    }

    // Phone Number
    var phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(form.phoneno.value.trim())) {
        alert("Please enter a valid 10-digit phone number.");
        form.phoneno.focus();
        return false;
    }

    // Age
    var age = parseInt(form.age.value.trim());
    if (isNaN(age) || age <= 0 || age > 120) {
        alert("Please enter a valid Age.");
        form.age.focus();
        return false;
    }

    // Identification type
    if (form.identi.value === "") {
        alert("Please select an Identification Type.");
        form.identi.focus();
        return false;
    }

    // File type checks are already done in submit event listener in another script
    // But you can double validate here if desired

    // Rent amount
    if (form.rent_amt.value.trim() === "" || parseFloat(form.rent_amt.value) < 0) {
        alert("Please enter a valid Rent Amount.");
        form.rent_amt.focus();
        return false;
    }

    // Deposit
    if (form.diposit.value.trim() === "" || parseFloat(form.diposit.value) < 0) {
        alert("Please enter a valid Deposit Amount.");
        form.diposit.focus();
        return false;
    }

    // Move-in Date
    if (form.movein.value === "") {
        alert("Please select Move-in Date.");
        form.movein.focus();
        return false;
    }

    // Lease Term Start
    if (form.t_start.value === "") {
        alert("Please select Lease Start Date.");
        form.t_start.focus();
        return false;
    }

    // Lease Period & Duration
    if (form.t_end.value.trim() === "") {
        alert("Please enter Lease Duration.");
        form.t_end.focus();
        return false;
    }

    if (form.lease_period.value === "") {
        alert("Please select Lease Period (Year/Month/Week).");
        form.lease_period.focus();
        return false;
    }

    // Payment Schedule
    if (form.pay_schedule.value === "") {
        alert("Please select a Payment Schedule.");
        form.pay_schedule.focus();
        return false;
    }

    // Police Verification
    if (form.police.value === "") {
        alert("Please select Police Verification status.");
        form.police.focus();
        return false;
    }

    // Emergency contact
    if (form.emg_name.value.trim() === "" || form.relation.value.trim() === "") {
        alert("Please fill Emergency Contact Name and Relationship.");
        return false;
    }

    if (!phoneRegex.test(form.emg_phone.value.trim())) {
        alert("Please enter a valid Emergency Contact phone number.");
        form.emg_phone.focus();
        return false;
    }

    if (form.emg_add.value.trim() === "") {
        alert("Please enter Emergency Contact Address.");
        form.emg_add.focus();
        return false;
    }

    return true;
}

       
// Function to auto-fill current date & time
    function showDateTime()
       {
         var d=new Date();

         var dd=d.getDate();
         var mm=d.getMonth()+1;
         var yy=d.getFullYear();


         var cdate=dd+"/"+mm+"/"+yy;

         document.querySelector('input[name="reg_date"]').value = cdate;
         document.querySelector('input[name="reg_time"]').value = d.toLocaleTimeString();
 
       }
       
       // Function to update building name in hidden input
     function updateBuildingName() {
        var select = document.getElementById("select_building");
        var buildingName = select.options[select.selectedIndex].text;
        document.getElementById("building_name").value = buildingName;
     }

       $(document).ready(function() {
        $('#select_building').on('change', function() {
          var buildingId = $(this).val();

          $.ajax({
          type: "POST",
          url: "fetch_units.php",
          data: { building_id: buildingId },
          success: function(response) {
            $('#unit_no_dropdown').html(response);
          },
           error: function(xhr, status, error) {
           console.error("AJAX Error:", error);
        }
      });
    });

  // After populating unit_no_dropdown, add a change event listener
    $(document).on('change', '#unit_no_dropdown', function() {
       var selectedOption = $(this).find('option:selected');
       var rent = selectedOption.data('rent');
       var deposit = selectedOption.data('deposit');

       $('#rent_amt').val(rent);
       $('#diposit_amt').val(deposit);
     });
   });


    document.addEventListener("DOMContentLoaded", function () {
    const tStartInput = document.querySelector('input[name="t_start"]');
    const tEndInput = document.querySelector('input[name="t_end"]');
    const leasePeriodSelect = document.querySelector('select[name="lease_period"]');
    const tEndDateInput = document.querySelector('input[name="t_end_date"]');

    function calculateEndDate() {
        const tStartValue = tStartInput.value;
        const tEndValue = parseInt(tEndInput.value);
        const leasePeriod = leasePeriodSelect.value;

        if (!tStartValue || isNaN(tEndValue) || !leasePeriod) {
            tEndDateInput.value = ''; // Clear if any field is missing
            return;
        }

        const startDate = new Date(tStartValue);
        let endDate = new Date(startDate);

        switch (leasePeriod) {
            case 'Year':
                endDate.setFullYear(endDate.getFullYear() + tEndValue);
                break;
            case 'Month':
                endDate.setMonth(endDate.getMonth() + tEndValue);
                break;
            case 'Week':
                endDate.setDate(endDate.getDate() + (tEndValue * 7));
                break;
        }

        const formattedDate = endDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD
        tEndDateInput.value = formattedDate;
    }

    // Attach listeners
    tStartInput.addEventListener('change', calculateEndDate);
    tEndInput.addEventListener('input', calculateEndDate);
    leasePeriodSelect.addEventListener('change', calculateEndDate);

    // File type validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        const file1 = document.querySelector('input[name="fileupload"]').files[0];
        const file2 = document.querySelector('input[name="agree_upload"]').files[0];

        if (file1 && !allowedTypes.includes(file1.type)) {
            alert("Invalid file type for identification upload.");
            e.preventDefault();
        }
        if (file2 && !allowedTypes.includes(file2.type)) {
            alert("Invalid file type for agreement upload.");
            e.preventDefault();
        }
    });
});

</script>
</head>

<body onload="showDateTime()">

    <div class="container">
        <form name="f1" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

           <div class="form">
               <h2 class="text-center">Add Tenant </h2>
       <hr style="height: 3px; background-color: grey;"> 
                
            <div class="row">
              <div class="col-sm-2">Select Property</div>
              <div class="col-sm-4">
                <select id="select_building" name="select_building" class="form-control" onchange="updateBuildingName()">
                    <option disabled selected value="">Select Property</option>
                    <?php
                        include("db_config.php"); 
                        $result = mysqli_query($conn, "SELECT b_no, building_name FROM building_info");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $b_id = $row['b_no'];
                            $b_name = $row['building_name'];
                            echo "<option value='$b_id'>$b_name</option>";
                        }
                    ?>
                </select>
                <input type="hidden" id="building_name" name="building_name">
             </div>

                <div class="col-sm-2">
                    Unit No.
                </div>

                <div class="col-sm-4">
                     <!-- <input type="text" name="unit_no" class="form-control" placeholder="eg. flat no, shop no. "> -->
                     <select name="unit_no" id="unit_no_dropdown" class="form-control">
                       <option value="">-- Select Unit No --</option>
                     </select>
                </div>

               </div> <!--  end of 1st row  -->

    <script>
    $(document).ready(function () {
        $('#unit_no_dropdown').on('change', function () {
    var type = $(this).find('option:selected').data('unit-type') || '';

    // Convert to lowercase for safe comparison
    type = type.toLowerCase();

    // Show industrial section only if 'shop' or 'shed'
    if (type === 'shop' || type === 'shed') {
      $('#industrial-section').show();
    } else {
      $('#industrial-section').hide();
    }

    // Hide any partner info if industrial section is hidden
    if (!$('#industrial-section').is(':visible')) {
      $('#partner-info-section').hide();
    }
  });

        // Show partner info if partnership type is selected
        $(document).on('change', 'input[name="partnership_type"]', function () {
            if ($(this).val() === "Partnership") {
                $('#partner-info-section').show();
            } else {
                $('#partner-info-section').hide();
            }
        });
    });
</script>

             <hr style="height: 3px; background-color: grey;"> <h4>Tenant Information</h4>

               <div class="row">
                    <div class="col-sm-2">
                         Date
                    </div>

                    <div class="col-sm-4">
                        <input type="text" name="reg_date" class="form-control" readonly>
                    </div>

                    <div class="col-sm-2">
                         Time
                    </div>

                    <div class="col-sm-4">
                        <input type="text" name="reg_time" class="form-control" readonly>
                    </div>

               </div>  <!--end of 1st row-->


               <div class="row">
                    <div class="col-sm-2"> Tenant Full Name </div>
                    <div class="col-sm-4">
                        <input type="text" name="tname" class="form-control">
                    </div>  

                    <div class="col-sm-2"> Username </div>
                    <div class="col-sm-4">
                        <input type="text" name="username" class="form-control">
                    </div>                    
               </div>  <!--end of 2nd row-->


               <div class="row">
                     <div class="col-sm-2">Tenant Address</div>
                     <div class="col-sm-10">
                        <input type="text" name="tenant_address" class="form-control">
                     </div>
               </div>     <!--end of 4th row-->


                <div class="row">
                     <div class="col-sm-2">Email</div>
                     <div class="col-sm-4">
                        <input type="email" name="email" class="form-control">
                     </div>

                      <div class="col-sm-2">Set Password</div>
                     <div class="col-sm-4">
                        <input type="text" name="password" class="form-control">
                     </div>
               </div>     <!--end of 4th row-->
                

               <div class="row">
                    <div class="col-sm-2">Phone No </div>
                    <div class="col-sm-4">
                        <input type="number" name="phoneno" class="form-control">
                    </div>

                    <div class="col-sm-2">  Age  </div>
                    <div class="col-sm-4">
                        <input type="text" name="age" class="form-control">
                    </div>
               </div>  <!--end of 3rd row-->


              
               <div class="row">
                    <div class="col-sm-2">
                         Pan Number
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="pan_no" class="form-control">
                    </div>

                    <div class="col-sm-2">
                        Aadhar Number
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="aadhar_no" class="form-control">
                    </div>
               </div>  <!--end of 3rd row-->



               <div class="row">
                    <div class="col-sm-2">
                       Identification Type
                    </div>
                    <div class="col-sm-4">
                         <select name="identi" class="form-control">
                           <option disabled selected value="">
                              ------Select Identification Type------
                           </option>
                           <option value="Adhar_card">Adhar card</option>
                           <option value="Passport">Passport</option>
                           <option value="Driving_licience">Driving licience</option>
                         </select>
                    </div>

                    <div class="col-sm-2">
                       Attached selected Identification
                    </div>
                    <div class="col-sm-4">
                        <input type="file" id="fileUpload" name="fileupload" class="small-upload" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
              </div>  <!--  end of 6th row  -->



              <div id="industrial-section" style="display:none;">
                <hr style="height: 3px; background-color: grey;"> 
                  <h4>Industrial Unit Details</h4>

              <div class="row">
                <div class="col-sm-2">Company Name</div>
                    <div class="col-sm-4">
                       <input type="text" name="company_name" class="form-control">
                    </div>

              <div class="col-sm-2">Ownership</div>
              <div class="col-sm-4">
               <label class="radio-inline">
                 <input type="radio" name="partnership_type" value="Sole Proprietorship" checked> Sole Proprietorship  
               </label>
               <label class="radio-inline">
                 <input type="radio" name="partnership_type" value="Partnership"> Partnership
               </label>
              </div>
           </div>


            <div class="row">
                     <div class="col-sm-2">Company Address</div>
                     <div class="col-sm-10">
                        <input type="text" name="company_address" class="form-control">
                     </div>

               </div>     <!--end of 4th row-->
        </div>


<div id="partner-info-section" style="display:none;">
    <hr style="height: 3px; background-color: grey;"> 
    <h4>Partner Information</h4>

    <div class="row">
        <div class="col-sm-2">Partner Name</div>
        <div class="col-sm-4">
            <input type="text" name="partner_name" class="form-control">
        </div>

        <div class="col-sm-2">Address</div>
        <div class="col-sm-4">
            <input type="text" name="partner_address" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">Phone No</div>
        <div class="col-sm-4">
            <input type="number" name="partner_phone" class="form-control">
        </div>

        <div class="col-sm-2">Email</div>
        <div class="col-sm-4">
            <input type="email" name="partner_email" class="form-control">
        </div>
    </div>
</div>


                <hr style="height: 3px; background-color: grey;"> <h4>Rent Information</h4>
                <p class="notes">"Note: The fixed Rent amount and Deposit amount is specified per unit." </p>

              <div class="row">
                   <div class="col-sm-2">
                      Rent Amount
                    </div>

                    <div class="col-sm-4">
                      <input type="number" name="rent_amt" class="form-control" oninput="calculateTotal()" id="rent_amt">
                      <!-- <input type="number" name="rent_amt" id="rent_amt" class="form-control"> -->


                    </div>


                    <div class="col-sm-2">
                      Diposit Amount
                    </div>

                    <div class="col-sm-4">
                        
                        <input type="number" name="diposit" class="form-control"  id="diposit_amt">
                    </div>

                   

               </div> <!--  end of 7th row  -->


            
                <div class="row">
                     <div class="col-sm-2">
                      Move-in Date
                    </div>

                    <div class="col-sm-4">
                      <input type="date" name="movein" class="form-control">
                    </div>


                     <div class="col-sm-2">
                      Diposit Date
                    </div>

                    <div class="col-sm-4">
                      <input type="date" name="diposit_date" class="form-control">
                    </div>

               </div> <!--  end of 7th row  -->

               <div class="row">
                     <div class="col-sm-2">
                      Move-out Date
                    </div>

                    <div class="col-sm-4">
                      <input type="date" name="moveout" class="form-control">
                    </div>

                    <div class="col-sm-2">
                      Payment Date
                    </div>

                    <div class="col-sm-4">
                      <input type="date" name="pay_date" class="form-control">
                    </div>

               </div> <!--  end of 7th row  -->



               <div class="row">
                    <div class="col-sm-2">
                      Lease Term Start
                    </div>

                    <div class="col-sm-2">
                        <input type="date" name="t_start" class="form-control">
                    </div>

                    <div class="col-sm-2">
                         <input type="file" id="fileUpload" name="agree_upload" class="small-upload" accept=".pdf,.jpg,.jpeg,.png">
                    </div> 

                    <div class="col-sm-2">
                      Lease Period
                    </div>

                    <div class="col-sm-2">
                      <input type="text" name="t_end" class="form-control">
                    </div>

                    <div class="col-sm-2">
                         <select name="lease_period" class="form-control">
                           <option value="">--select lease--</option>
                           <option value="Year">Years</option>
                           <option value="Month">Months</option>
                           <option value="Week">Weeks </option>
                         </select>
                    </div>

               </div> <!--  end of 8th row  -->


               <div class="row">
                    <div class="col-sm-4" style="display:none;">
                       <input type="text" name="t_end_date" class="form-control" readonly >
                    </div>

                    <div class="col-sm-2">
                       Payment Schedule
                    </div>

                    <div class="col-sm-4">
                         <select name="pay_schedule" class="form-control">
                           <option disabled selected value="">------Select Payment Schedule ------</option>
                           <option value="Weekly">Weekly</option>
                           <option value="Monthly">Monthly</option>
                           <option value="Annually">Annually</option>
                         </select>
                    </div>


                     <div class="col-sm-2">Police Verification</div>
                        <div class="col-sm-4">
                          <select name="police" class="form-control">
                            <option value="">----Select Verified or Not---</option>
                            <option value="Verified">Verified</option>
                            <option value="Not-Verified">Not-Verified</option>
                          </select>     
                        </div>
                </div>



               <hr style="height: 3px; background-color: grey;"> <h4>Emergency Contact</h4>

                 <div class="row">
                      <div class="col-sm-2"> Name </div>
                      <div class="col-sm-4"> <input type="text" name="emg_name" class="form-control"> </div>

                      <div class="col-sm-2"> Relationship </div>
                      <div class="col-sm-4"> <input type="text" name="relation" class="form-control"> </div>

                 </div> <!--  end of 10th row  -->


                <div class="row">
                    <div class="col-sm-2"> Phone Number </div>
                    <div class="col-sm-4"> <input type="number" name="emg_phone" class="form-control"> </div>

                    <div class="col-sm-2"> Address </div>
                    <div class="col-sm-4"> <input type="text" name="emg_add" class="form-control"> </div>
               </div> <!--  end of 11th row  -->


               <div class="row">
                   <div class="col-sm-12 text-center" >
                   <!--   <a href="dashboard.php">
                      <button type="button" class="btn">Go Back</button>
                     </a>  -->

                     <?php
                        $section = isset($_GET['section']) ? $_GET['section'] : 'tenant';
                        echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
                     ?>
                 
                     <button type="submit" name="submit_reg" class="btn" onclick="return validate()">Submit</button>
                     <button type="reset" class="btn">Reset</button>
                     
                    <!--  <a href="logout.php">
                     <button type="button" class="btn">Log Out</button>
                    </a> -->
                   </div>
               </div>

<?php
        
if(isset($_POST["submit_reg"]))
{

   include("db_config.php");

    $select_building=$_POST["select_building"];
    $building_name = $_POST["building_name"];
    $unit_no = $_POST["unit_no"];
    $reg_date=$_POST["reg_date"];
    $reg_time=$_POST["reg_time"];
    $tname=$_POST["tname"];
    $username=$_POST["username"];
    $tenant_address=$_POST["tenant_address"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $phoneno=$_POST["phoneno"];
    $age=$_POST["age"];
    $pan_no=$_POST["pan_no"];
    $aadhar_no=$_POST["aadhar_no"];
    $identification=$_POST["identi"];

    $fileupload_path = '';
    $agreeupload_path = '';

  
// Handle fileupload (ID proof)
if (isset($_FILES['fileupload']) && $_FILES['fileupload']['error'] == 0) {
    $file_name = basename($_FILES['fileupload']['name']);
    $target_dir = "uploads/"; // Ensure this directory exists and is writable
    $fileupload_path = $target_dir . time() . "_" . $file_name;
    move_uploaded_file($_FILES['fileupload']['tmp_name'], $fileupload_path);
}

// Handle agree_upload (agreement file)
if (isset($_FILES['agree_upload']) && $_FILES['agree_upload']['error'] == 0) {
    $file_name2 = basename($_FILES['agree_upload']['name']);
    $target_dir = "uploads/";
    $agreeupload_path = $target_dir . time() . "_" . $file_name2;
    move_uploaded_file($_FILES['agree_upload']['tmp_name'], $agreeupload_path);
}

    $rent_amt=$_POST["rent_amt"];
    $diposit=$_POST["diposit"];
    $movein=$_POST["movein"];
    $moveout=$_POST["moveout"];
    $diposit_date=$_POST["diposit_date"];
    $pay_schedule=$_POST["pay_schedule"];
    $pay_date=$_POST["pay_date"];
    $t_start=$_POST["t_start"];
    $t_end=$_POST["t_end"];
    $lease_period=$_POST["lease_period"];
    $t_end_date=$_POST["t_end_date"];
    $police=$_POST["police"];

    $emg_name=$_POST["emg_name"];
    $relation=$_POST["relation"];
    $emg_phone=$_POST["emg_phone"];
    $emg_add=$_POST["emg_add"];

    $company_name = $_POST["company_name"];
    $partnership_type = $_POST["partnership_type"];
    $company_address=$_POST["company_address"];
    $partner_name = $_POST["partner_name"];
    $partner_address =$_POST["partner_address"];
    $partner_phone = $_POST["partner_phone"];
    $partner_email = $_POST["partner_email"];



    $sql="insert into add_tenant(select_building,building_name,unit_no,reg_date,reg_time,tname,username,tenant_address,email,password,phoneno,age,pan_no,
    aadhar_no,identi,rent_amt,diposit,movein,moveout,diposit_date,pay_schedule,pay_date,t_start,t_end,lease_period,t_end_date,police,emg_name,relation,emg_phone,emg_add,fileupload, agree_upload,company_name,partnership_type,company_address,partner_name, partner_address,partner_phoneno,partner_email)values('$select_building', '$building_name', '$unit_no', '$reg_date', '$reg_time', '$tname','$username','$tenant_address', '$email','$password', 
    '$phoneno', '$age', '$pan_no', '$aadhar_no', '$identification', '$rent_amt', '$diposit', '$movein', '$moveout',
    '$diposit_date', '$pay_schedule', '$pay_date', '$t_start', '$t_end', '$lease_period', '$t_end_date', '$police', 
    '$emg_name', '$relation', '$emg_phone', '$emg_add', '$fileupload_path', '$agreeupload_path', '$company_name', 
    '$partnership_type', '$company_address', '$partner_name', '$partner_address', '$partner_phone', '$partner_email')";


    if(mysqli_query($conn,$sql)) {
    echo "<div class='row text-center'>Data Inserted...</div>";
} else {
    echo "<div class='row text-center'>Error: " . mysqli_error($conn) . "</div>";
}


}//end of issset 


      ?>

           </div>    <!-- end of form  div-->

        </form>         
    </div>


</body>
</html>
