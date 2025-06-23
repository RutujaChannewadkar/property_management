<?php
include("db_config.php");

$t_no = $_GET['t_no'] ?? null;
$tenant = null;

if ($t_no) {
    $stmt = $conn->prepare("SELECT * FROM add_tenant WHERE t_no = ?");
    $stmt->bind_param("i", $t_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $tenant = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenant Information</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .tenant-info {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { color: #333; margin-bottom: 10px; }
        h3 { margin-top: 25px; color: #555; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        p { line-height: 1.5; margin: 6px 0; }
        strong {
            display: inline-block;
            width: 160px;
            color: #222;
        }

        .btn-secondary-custom {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;       /* Bootstrap secondary gray */
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            border-radius: 5px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
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
</head>
<body>
    <div class="tenant-info">
        <h2>Tenant Information Report</h2>

        <?php if ($tenant): ?>
            <h3>Basic Details</h3>
            <p><strong>Tenant ID:</strong> <?= htmlspecialchars($tenant['t_no']) ?></p>
            <!-- <p><strong>Building Selected:</strong> <?= htmlspecialchars($tenant['select_building']) ?></p> -->
            <p><strong>Building Name:</strong> <?= htmlspecialchars($tenant['building_name']) ?></p>
            <p><strong>Unit Number:</strong> <?= htmlspecialchars($tenant['unit_no']) ?></p>

            <h3>Registration Details</h3>
            <p><strong>Registration Date:</strong> <?= htmlspecialchars($tenant['reg_date']) ?></p>
            <p><strong>Registration Time:</strong> <?= htmlspecialchars($tenant['reg_time']) ?></p>
            <p><strong>Move-in Date:</strong> <?= htmlspecialchars($tenant['movein']) ?></p>
            <p><strong>Move-out Date:</strong> <?= htmlspecialchars($tenant['moveout']) ?></p>

            <p><strong>Deposit Date:</strong> <?= htmlspecialchars($tenant['diposit_date']) ?></p>
            <p><strong>Tenancy Start Date:</strong> <?= htmlspecialchars($tenant['t_start']) ?></p>
            <p><strong>Tenancy End Date:</strong> <?= htmlspecialchars($tenant['t_end_date']) ?></p>
            <!-- <p><strong>Lease Period:</strong> <?= htmlspecialchars($tenant['lease_period']) ?></p> -->
            <!-- <p><strong>Calculated End Date:</strong> <?= htmlspecialchars($tenant['t_end_date']) ?></p> -->

            <h3>Tenant Personal Details</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($tenant['tname']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($tenant['age']) ?></p>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($tenant['tenant_address'])) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($tenant['email']) ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($tenant['phoneno']) ?></p>
            <p><strong>PAN Number:</strong> <?= htmlspecialchars($tenant['pan_no']) ?></p>
            <p><strong>Aadhar Number:</strong> <?= htmlspecialchars($tenant['aadhar_no']) ?></p>
            <p><strong>ID Proof:</strong> <?= htmlspecialchars($tenant['identi']) ?></p>

            <h3>Rent & Payment Details</h3>
            <p><strong>Rent Amount:</strong> ₹<?= htmlspecialchars($tenant['rent_amt']) ?></p>
            <p><strong>Deposit Amount:</strong> ₹<?= htmlspecialchars($tenant['diposit']) ?></p>
            <p><strong>Payment Schedule:</strong> <?= htmlspecialchars($tenant['pay_schedule']) ?></p>
            <p><strong>Payment Date:</strong> <?= htmlspecialchars($tenant['pay_date']) ?></p>

            <h3>Legal & Safety</h3>
            <p><strong>Police Verified:</strong> <?= htmlspecialchars($tenant['police']) ?></p>

            <h3>Uploaded Files</h3>
            <p><strong>Tenant ID Upload:</strong> <?= htmlspecialchars($tenant['fileupload']) ?></p>
            <p><strong>Agreement Upload:</strong> <?= htmlspecialchars($tenant['agree_upload']) ?></p>

            <h3>Emergency Contact</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($tenant['emg_name']) ?></p>
            <p><strong>Relation:</strong> <?= htmlspecialchars($tenant['relation']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($tenant['emg_phone']) ?></p>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($tenant['emg_add'])) ?></p>

            <?php if (!empty($tenant['company_name'])): ?>
                <h3>Company / Partnership Details</h3>
                <p><strong>Company Name:</strong> <?= htmlspecialchars($tenant['company_name']) ?></p>
                <p><strong>Partnership Type:</strong> <?= htmlspecialchars($tenant['partnership_type']) ?></p>
                <p><strong>Company Address:</strong> <?= nl2br(htmlspecialchars($tenant['company_address'])) ?></p>

                <?php if (strtolower($tenant['partnership_type']) !== 'individual'): ?>
                    <p><strong>Partner Name:</strong> <?= htmlspecialchars($tenant['partner_name']) ?></p>
                    <p><strong>Partner Address:</strong> <?= nl2br(htmlspecialchars($tenant['partner_address'])) ?></p>
                    <p><strong>Partner Phone:</strong> <?= htmlspecialchars($tenant['partner_phoneno']) ?></p>
                    <p><strong>Partner Email:</strong> <?= htmlspecialchars($tenant['partner_email']) ?></p>
                <?php endif; ?>
            <?php endif; ?>


            <hr>
            <p><em>Report generated on: <?= date('Y-m-d H:i:s') ?></em></p>
            <p><strong>Confidential – For Internal Use Only</strong></p>
        <?php else: ?>
            <p><strong>Tenant not found.</strong></p>
        <?php endif; ?>

        <a href="occupancy_details.php" class="btn btn-secondary-custom">Go back</a>
    </div>
</body>
</html>
