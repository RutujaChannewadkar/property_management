<?php
// Database connection
include("db_config.php");

// Fetch tenants for dropdown
$sqlAllTenants = "SELECT t_no, tname FROM add_tenant ORDER BY tname ASC";
$resultAllTenants = $conn->query($sqlAllTenants);
$tenants = [];
if ($resultAllTenants) {
    while ($row = $resultAllTenants->fetch_assoc()) {
        $tenants[] = $row;
    }
}

// Get tenant ID and invoice month (YYYY-MM) from query params
$t_no = isset($_GET['t_no']) ? intval($_GET['t_no']) : 0;
$invoice_month = isset($_GET['invoice_month']) ? $_GET['invoice_month'] : date("Y-m");

// Form validation and invoice only if tenant selected
if ($t_no && $invoice_month) {
    // Validate invoice_month format
    if (!preg_match('/^\d{4}-\d{2}$/', $invoice_month)) {
        die("Invalid invoice month format. Use YYYY-MM.");
    }

    // Fetch tenant info
    $sqlTenant = "SELECT * FROM add_tenant WHERE t_no = ?";
    $stmtTenant = $conn->prepare($sqlTenant);
    $stmtTenant->bind_param("i", $t_no);
    $stmtTenant->execute();
    $resultTenant = $stmtTenant->get_result();

    if ($resultTenant->num_rows === 0) {
        die("Tenant not found.");
    }
    $tenant = $resultTenant->fetch_assoc();

    // Get building info using tenant's select_building field (which equals building_info.b_no)
    $sqlBuilding = "SELECT * FROM building_info WHERE b_no = ?";
    $stmtBuilding = $conn->prepare($sqlBuilding);
    $stmtBuilding->bind_param("i", $tenant['select_building']);
    $stmtBuilding->execute();
    $resultBuilding = $stmtBuilding->get_result();
    $building = $resultBuilding->fetch_assoc();

    if (!$building) {
        die("Building not found.");
    }

    // Now get owner info using building_info.select_owner
    $sqlOwner = "SELECT * FROM owner_info WHERE owner_id = ?";
    $stmtOwner = $conn->prepare($sqlOwner);
    $stmtOwner->bind_param("i", $building['select_owner']);
    $stmtOwner->execute();
    $resultOwner = $stmtOwner->get_result();
    $owner = $resultOwner->fetch_assoc();

    if (!$owner) {
        die("Owner info not found.");
    }

    // Monthly rent and maintenance (maintenance assumed 0 here)
    $monthly_rent = floatval($tenant['rent_amt']);
    $maintenance = 0; // change if you have maintenance stored

    // Use movein date as tenancy start date
    $t_start = $tenant['movein'];
    if (!$t_start) die("Tenant move-in date missing.");

    // Calculate months from move-in to invoice month (inclusive)
    $startDate = new DateTime($t_start);
    $invoiceDate = new DateTime($invoice_month . "-01");

    if ($invoiceDate < $startDate) {
        die("Invoice month is before tenant move-in date.");
    }

    $diff = $startDate->diff($invoiceDate);
    $monthsElapsed = $diff->y * 12 + $diff->m + 1; // +1 to include invoice month

    // Fetch last payment amount for tenant & unit
    $sqlLastPayment = "SELECT amount_paid FROM payments_info WHERE tenant_name = ? AND unit_no = ? ORDER BY pay_date DESC LIMIT 1";
    $stmtLastPayment = $conn->prepare($sqlLastPayment);
    $stmtLastPayment->bind_param("ss", $tenant['tname'], $tenant['unit_no']);
    $stmtLastPayment->execute();
    $resultLastPayment = $stmtLastPayment->get_result();
    $lastPaymentRow = $resultLastPayment->fetch_assoc();
    $lastPaymentAmount = floatval($lastPaymentRow['amount_paid'] ?? 0);

    // Calculate pending amount
    if ($lastPaymentAmount <= 0) {
        // No payment done, pending = rent * months elapsed
        $pendingAmount = $monthly_rent * $monthsElapsed;
    } else {
        // Payment done, pending = shortfall this month only
        $pendingAmount = max(0, $monthly_rent - $lastPaymentAmount);
    }

    // Total amount due on this invoice (monthly rent + pending amount)
    $totalAmountToPay = $monthly_rent + $pendingAmount;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rent Invoice - <?php echo htmlspecialchars($invoice_month); ?></title>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  
<script>

   async function saveAsPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // Elements to hide before generating PDF
    const elementsToHide = [document.querySelector('form')]; // Add any other selectors if needed

    // Hide the elements
    elementsToHide.forEach(el => {
        if(el) el.style.display = 'none';
    });

    const element = document.querySelector('.invoice-box');

    // Generate canvas of invoice only
    const canvas = await html2canvas(element, { scale: 2 });
    const imgData = canvas.toDataURL('image/png');

    const imgProps = doc.getImageProperties(imgData);
    const pdfWidth = doc.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

    const tenantName = "<?php echo addslashes($tenant['tname'] ?? 'tenant'); ?>";
    const safeTenantName = tenantName.replace(/\s+/g, '_').toLowerCase();

    // Show the hidden elements back
    elementsToHide.forEach(el => {
        if(el) el.style.display = '';
    });

    doc.save(`invoice_${safeTenantName}.pdf`);
}


</script>


    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box {
            width: 700px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px #ccc;
        }
        h2 { text-align: center; }
        
        table { width: 100%; line-height: 1.5; border-collapse: collapse; }
        
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        
        .total { font-weight: bold; }
        
        form {
            width: 700px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        form label {
            min-width: 120px;
        }

        form select, form input[type="month"] {
            padding: 5px;
            font-size: 1em;
        }

        form button {
            padding: 7px 15px;
            font-size: 1em;
            cursor: pointer;
        }

        a.go-back-button {
             display: inline-block;
             padding: 7px 25px;
             background-color: #ebedef ;
             color: black;
             text-decoration: none;
             font-size: 1em;
             border: 1px solid;
             cursor: pointer;
             transition: background-color 0.3s ease;
           }

        a.go-back-button:hover {
            background-color:  #d5d8dc ;
            border: 1px solid;
            
          }

    </style>

</head>
<body>

<!-- Tenant select + invoice month form -->
<form method="get" action="">
    <label for="t_no">Select Tenant:</label>
    <select name="t_no" id="t_no" required>
        <option value="">-- Select Tenant --</option>
        <?php foreach ($tenants as $t): ?>
            <option value="<?php echo $t['t_no']; ?>" <?php if ($t_no == $t['t_no']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($t['tname']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="invoice_month">Invoice Month (YYYY-MM):</label>
    <input type="month" name="invoice_month" id="invoice_month" value="<?php echo htmlspecialchars($invoice_month); ?>" required>

    <button type="submit">Show Invoice</button>
     <button onclick="saveAsPDF()" type="button">Save as PDF</button>
      <?php
            $section = isset($_GET['section']) ? $_GET['section'] : 'reports';
            echo "<a href='dashboard.php#{$section}' class='go-back-button' >Go back</a>";
        ?> 

</form>

<?php if ($t_no && $invoice_month): ?>
<div class="invoice-box">
    <h2>Rent Invoice - <?php echo htmlspecialchars($invoice_month); ?></h2>

    <p><strong>Landlord/Property Manager:</strong><br>
   Name: <?php echo htmlspecialchars($owner['oname'] ?? 'N/A'); ?><br>
   Phone: <?php echo htmlspecialchars($owner['phone'] ?? $owner['phoneno'] ?? $owner['phone_no'] ?? 'N/A'); ?><br>
   Email: <?php echo htmlspecialchars($owner['email'] ?? $owner['email_address'] ?? 'N/A'); ?></p>


    <hr>

    <p><strong>Tenant:</strong> <?php echo htmlspecialchars($tenant['tname']); ?></p>
    <p><strong>Unit:</strong> <?php echo htmlspecialchars($tenant['unit_no']); ?> | <strong>Building:</strong> <?php echo htmlspecialchars($tenant['building_name']); ?></p>
    <p><strong>Move-in Date:</strong> <?php echo htmlspecialchars($tenant['movein']); ?></p>

    <hr>

    <table>
        <tr>
            <th>Description</th>
            <th>Amount (USD)</th>
        </tr>
        <tr>
            <td>Monthly Rent</td>
            <td>$<?php echo number_format($monthly_rent, 2); ?></td>
        </tr>
        <tr>
            <td>Pending Amount (from last payment or accumulated unpaid months)</td>
            <td>$<?php echo number_format($pendingAmount, 2); ?></td>
        </tr>
        <tr class="total">
            <td>Total Amount to Pay</td>
            <td>$<?php echo number_format($totalAmountToPay, 2); ?></td>
        </tr>
    </table>

    <hr>

    <p>Please make payment by the due date to avoid late fees.</p>

    <p style="margin-top: 20px;">Thank you for your payment.</p>

   
   
</div>
<?php endif; ?>

</body>
</html>
