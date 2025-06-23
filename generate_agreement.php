<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Agreement</title>
</head>
<body>

<style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f9f9f9;
    padding: 20px;
}

h2, h3 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
}

p {
    max-width: 900px;
    margin: 12px auto;
    font-size: 16px;
    color: #444;
}

strong {
    color: #2c3e50;
}

ol {
    max-width: 900px;
    margin: 20px auto;
    padding-left: 20px;
    color: #444;
}

ol li {
    margin-bottom: 15px;
    font-size: 16px;
}

table {
    width: 900px;
    margin: 40px auto;
    border-collapse: collapse;
}

table td {
    width: 50%;
    padding: 15px 20px;
    border: 1px solid #ddd;
    vertical-align: top;
    font-size: 16px;
    color: #444;
}

table td strong {
    display: block;
    margin-bottom: 10px;
}

p strong {
    font-weight: 700;
}

@media (max-width: 960px) {
    body, p, ol, table {
        width: 90%;
        max-width: 100%;
        margin: 12px auto;
    }
    
    table td {
        display: block;
        width: 100%;
        box-sizing: border-box;
        border: none;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    
    table tr {
        margin-bottom: 20px;
        display: block;
    }
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
  async function saveAsPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    
    const element = document.body; // or select your specific agreement container

    // Use html2canvas to capture the element as canvas
    const canvas = await html2canvas(element, { scale: 2 });

    const imgData = canvas.toDataURL('image/png');
    const imgProps = doc.getImageProperties(imgData);
    const pdfWidth = doc.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    doc.save('agreement.pdf');
  }
</script>



<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $building_id = mysqli_real_escape_string($conn, $_POST['building']);
    $unit_no = mysqli_real_escape_string($conn, $_POST['unit_no']);

    // Fetch building and owner info
    $building_query = "
        SELECT 
            b.building_name,
            b.address AS building_address,
            o.oname AS owner_name,
            o.age AS owner_age,
            o.pan_no AS owner_pan,
            o.aadhar_no AS owner_aadhar,
            o.address AS owner_address,
            o.email AS owner_email,
            o.phoneno AS owner_phone
        FROM building_info b
        JOIN owner_info o ON b.select_owner = o.owner_id
        WHERE b.b_no = '$building_id'
    ";
    $building_result = mysqli_query($conn, $building_query);
    $building_data = mysqli_fetch_assoc($building_result);

    if (!$building_data) {
        die("Building or owner information not found.");
    }

    // Fetch tenant info
    $tenant_query = "
        SELECT 
            t.tname AS tenant_name,
            t.tenant_address,
            t.email AS tenant_email,
            t.phoneno AS tenant_phone,
            t.age AS tenant_age,
            t.pan_no AS tenant_pan,
            t.aadhar_no AS tenant_aadhar,
            t.identi AS tenant_identity,
            t.t_start AS start_date,
            t.t_end_date AS end_date,
            t.lease_period,
            t.company_name,
            t.company_address,
            t.partnership_type,
            t.diposit,
            t.rent_amt,
            t.partner_name,
            t.partner_address AS partner_address,
            t.partner_phoneno,
            t.partner_email,
            t.police,
            t.emg_name,
            t.relation,
            t.emg_phone,
            t.emg_add
        FROM add_tenant t
        WHERE t.select_building = '$building_id' AND t.unit_no = '$unit_no'
        ORDER BY t.reg_date DESC LIMIT 1
    ";
    $tenant_result = mysqli_query($conn, $tenant_query);
    $tenant_data = mysqli_fetch_assoc($tenant_result);

    if (!$tenant_data) {
        die("Tenant data not found.");
    }

    // Fetch unit info for area and type
    $unit_query = "
        SELECT area_sqft, unit_type, floor
        FROM unit_info
        WHERE building_name = '{$building_data['building_name']}' AND unit_no = '$unit_no'
    ";
    $unit_result = mysqli_query($conn, $unit_query);
    $unit_data = mysqli_fetch_assoc($unit_result);

    $unit_area = $unit_data['area_sqft'] ?? 'N/A';
    $unit_type = $unit_data['unit_type'] ?? 'N/A';
    $unit_floor = $unit_data['floor'] ?? 'N/A';

    // Format dates
    $start_date = date("d/m/Y", strtotime($tenant_data['start_date']));
    $end_date = date("d/m/Y", strtotime($tenant_data['end_date']));

   $rent_yr1 = (float)$tenant_data['rent_amt'];
   $rent_yr2 = number_format($rent_yr1 * 1.10, 2);
   $rent_yr3 = number_format($rent_yr1 * 1.21, 2);
   $rent_yr1_formatted = number_format($rent_yr1, 2);


    // Agreement date
    $agreement_date = date("d/m/Y");

    // Begin output
    echo "<h2 style='text-align:center;'>LEAVE AND LICENSE AGREEMENT</h2>";
    echo "<p>This agreement is made and executed on <strong>$agreement_date</strong> at Pune.</p>";

    echo "<p><strong>Between,</strong><br>";
    echo "1) Name: Mr./Ms. <strong>{$building_data['owner_name']}</strong>, Age: About <strong>{$building_data['owner_age']}</strong> Years,<br>";
    echo "PAN: <strong>{$building_data['owner_pan']}</strong><br>";
    echo "Residing at: <strong>{$building_data['owner_address']}</strong><br>";
    echo "Phone: <strong>{$building_data['owner_phone']}</strong>, Email: <strong>{$building_data['owner_email']}</strong><br>";
    echo "HEREINAFTER called ‘the Licensor’ (which expression shall mean and include the Licensor above named and also his/her/their respective heirs, successors, assigns, executors and administrators)</p>";

    echo "<p><strong>AND</strong><br>";
    echo "1) ";
    if (!empty($tenant_data['company_name'])) {
        echo "M/s <strong>{$tenant_data['company_name']}</strong>, a <strong>{$tenant_data['partnership_type']}</strong>,<br>";
        echo "Registered at: <strong>{$tenant_data['company_address']}</strong>";
        echo "through Authorized Signatory Mr./Ms. <strong>{$tenant_data['tenant_name']}</strong>, </strong>, Age: About <strong>{$tenant_data['tenant_age']}</strong> Years,<br>";
        echo "PAN: <strong>{$tenant_data['tenant_pan']}</strong>, Aadhar No.: <strong>{$tenant_data['tenant_aadhar']}</strong><br>";
        echo "Phone: <strong>{$tenant_data['tenant_phone']}</strong>, Email: <strong>{$tenant_data['tenant_email']}</strong><br>";
    } else {
        echo "Mr./Ms. <strong>{$tenant_data['tenant_name']}</strong>,<br>";
        echo "Residing at: <strong>{$tenant_data['tenant_address']}</strong>,<br>";
        echo "Email: <strong>{$tenant_data['tenant_email']}</strong>, Phone: <strong>{$tenant_data['tenant_phone']}</strong><br>";
        echo "Age: About <strong>{$tenant_data['tenant_age']}</strong> Years, PAN: <strong>{$tenant_data['tenant_pan']}</strong>, Aadhar No.: <strong>{$tenant_data['tenant_aadhar']}</strong><br>";
    }
    echo "HEREINAFTER called ‘the Licensee’ (which expression shall mean and include only Licensee above named).</p>";

    echo "<p><strong>WHEREAS</strong> the Licensor is absolutely seized and possessed of and/or otherwise well and sufficiently entitled to all that constructed portion being unit described in Schedule I hereunder written and hereafter called the Licensed Premises and is desirous of giving the said premises on Leave and License basis under Section 24 of the Maharashtra Rent Control Act, 1999.</p>";

    echo "<p>AND WHEREAS the Licensee herein is in need of temporary premises for Non-Residential use and has approached the Licensor with a request to allow the Licensee to use and occupy the said premises on Leave and License basis for a period of <strong>{$tenant_data['lease_period']}</strong> months commencing from <strong>$start_date</strong> and ending on <strong>$end_date</strong>, on terms and subject to conditions hereafter appearing.</p>";

    echo "<p>AND WHEREAS the Licensor has agreed to allow the Licensee to use and occupy the said Licensed premises for aforesaid Non-Residential purposes only, on Leave and License basis for the above period, on terms and conditions hereafter appearing;</p>";

    echo "<p><strong>NOW THEREFORE IT IS HEREBY AGREED, DECLARED AND RECORDED BY AND BETWEEN THE PARTIES HERETO AS FOLLOWS:</strong></p>";

    echo "<ol>";
    echo "<li><strong>Period:</strong> The Licensor hereby grants to the Licensee a revocable leave and license to occupy the Licensed Premises described in Schedule I without creating any tenancy rights or other rights, title, and interest in favor of the Licensee for a period of <strong>{$tenant_data['lease_period']}</strong> months commencing from <strong>$start_date</strong> and ending on <strong>$end_date</strong>.</li>";
    echo "<li><strong>License Fee & Deposit:</strong><br>
        The Licensee shall pay to the Licensor monthly compensation of:<br>
        a) ₹$rent_yr1_formatted per month for the 1st year,<br>
        b) ₹$rent_yr2 per month for the 2nd year,<br>
        c) ₹$rent_yr3 per month for the 3rd year.<br>;

        The monthly license fee shall be payable within the first five days of each month.<br>
        The Licensee shall also pay a refundable, interest-free deposit of ₹{$tenant_data['diposit']} (Rupees in words to be added) as security for the Licensed Premises.</li>";
    echo "<li><strong>Payment of Deposit:</strong> The deposit has been paid / shall be paid by cash/bank transfer as agreed.</li>";
    echo "<li><strong>Maintenance Charges:</strong> All outgoings including rates, taxes, levies, assessment, maintenance charges, and non-occupancy charges shall be borne by the Licensor.</li>";
    echo "<li><strong>Electricity Charges:</strong> The Licensee shall pay electricity bills directly and submit original receipts to the Licensor.</li>";
    echo "<li><strong>Use:</strong> The Licensed premises shall be used by the Licensee solely for Non-Residential purposes. The Licensee shall maintain the premises in existing condition and repair any damage at their own cost except for normal wear and tear. No nuisance or unlawful activity is permitted.</li>";
    echo "<li><strong>Alteration:</strong> The Licensee shall not make any alteration or addition without prior written consent of the Licensor.</li>";
    echo "<li><strong>No Tenancy Rights:</strong> This agreement does not create tenancy rights and the Licensee shall not assign, sublet, transfer, or mortgage the Licensed Premises.</li>";
    echo "<li><strong>Inspection:</strong> The Licensor or authorized representative may inspect the premises with reasonable notice.</li>";
    echo "<li><strong>Cancellation:</strong> Either party may terminate this agreement by giving one month's written notice. In case of two months' non-payment of license fee, this agreement shall automatically terminate without notice.</li>";
    echo "<li><strong>Vacating:</strong> The Licensee shall vacate the premises on or before the expiry of the license period and hand over peaceful possession to the Licensor.</li>";
    echo "<li><strong>Stamp Duty & Registration:</strong> The charges for stamp duty, registration, and other applicable fees shall be borne equally by both parties.</li>";
    echo "<li><strong>GST:</strong> Goods and Service Tax, if applicable, shall be payable by the Licensee.</li>";
    echo "</ol>";

    echo "<h3>SCHEDULE I</h3>";
    echo "<p><strong>Premises:</strong> Unit No. <strong>$unit_no</strong>, Floor: <strong>$unit_floor</strong>, {$building_data['building_name']}<br>";
    echo "<strong>Address:</strong> {$building_data['building_address']}<br>";
    echo "<strong>Type:</strong> $unit_type, <strong>Built-up Area:</strong> $unit_area Sq.Ft.<br>";
    echo "Village: Vadgaon Budruk, Pune - 411041</p>";

    echo "<p><strong>IN WITNESS WHEREOF</strong> the parties have executed this Leave and License Agreement on the day, month, and year first above written.</p>";

    echo "<table style='width:100%; margin-top:40px;'>";
    echo "<tr>";
    echo "<td><strong>Licensor:</strong><br><br>Signature: ___________________<br>Name: <strong>{$building_data['owner_name']}</strong></td>";
    echo "<td><strong>Licensee:</strong><br><br>Signature: ___________________<br>Name: <strong>{$tenant_data['tenant_name']}</strong> (Authorized Signatory)</td>";
    echo "</tr>";
    echo "</table>";

    echo "<p><strong>Witnesses:</strong><br><br>";
    echo "1. Signature: ____________________ Name: ____________________ Address: ____________________<br><br>";
    echo "2. Signature: ____________________ Name: ____________________ Address: ____________________</p>";
}
?>



<div class="action-buttons" style="text-align:center; margin: 20px 0;">
  <button onclick="window.print()" class="btn btn-print">Print</button>
  <button onclick="saveAsPDF()" class="btn btn-pdf">Save as PDF</button>
   <?php
        $section = isset($_GET['section']) ? $_GET['section'] : 'generateAgreement';
        echo "<a href='dashboard.php#{$section}' class='btn btn-secondary-custom'>Go back</a>";
    ?>    
</div>

</body>
</html>