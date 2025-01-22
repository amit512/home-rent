<?php
// Include the database connection file
require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');// Ensure the $connect PDO instance is available

// Fetch all pending and verified KYC submissions
$pendingStmt = $connect->prepare("SELECT * FROM kyc_verifications WHERE status = 'Pending'");
$pendingStmt->execute();
$pendingKycSubmissions = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);

$verifiedStmt = $connect->prepare("SELECT * FROM kyc_verifications WHERE status = 'Verified'");
$verifiedStmt->execute();
$verifiedKycSubmissions = $verifiedStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle admin actions (approve/reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // 'approve' or 'reject'
    $kycId = $_POST['kyc_id'];

    if (!in_array($action, ['approve', 'reject'])) {
        echo "<div class='alert alert-danger'>Invalid action.</div>";
    } else {
        $status = $action === 'approve' ? 'Verified' : 'Rejected';
        $updateStmt = $connect->prepare("UPDATE kyc_verifications SET status = ? WHERE id = ?");
        $updateStmt->execute([$status, $kycId]);

        echo "<div class='alert alert-success'>" . ucfirst($action) . " action successfully applied.</div>";
        header("Refresh: 2"); // Refresh the page after action
        exit;
    }
}
?>
<?php include '../include/header.php';?>
<!-- Header nav -->	
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="../index.php">Logo/Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
            </li>
            <li class="nav-item">
              <a href="logout.php" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<!-- end header nav -->
    <?php include '../include/side-nav.php';?>
<div class="container" style="margin-left: 16%;">
    <h1>KYC Management</h1>
    <!-- Pending KYC Submissions -->
    <h2>Pending Submissions</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>KYC ID</th>
                <th>User Name</th>
                <th>Mobile</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Residential Proof</th>
                <th>ID Proof</th>
                <th>Selfie</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pendingKycSubmissions) > 0): ?>
                <?php foreach ($pendingKycSubmissions as $kyc): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($kyc['id']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['father_name']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['mother_name']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['residential_proof_path']); ?>" target="_blank">View</a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['id_proof_path']); ?>" target="_blank">View</a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['selfie_path']); ?>" target="_blank">View</a>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="kyc_id" value="<?php echo htmlspecialchars($kyc['id']); ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="kyc_id" value="<?php echo htmlspecialchars($kyc['id']); ?>">
                                <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No pending KYC submissions.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Verified KYC Submissions -->
    <h2>Verified Submissions</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>KYC ID</th>
                <th>User Name</th>
                <th>Mobile</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Residential Proof</th>
                <th>ID Proof</th>
                <th>Selfie</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($verifiedKycSubmissions) > 0): ?>
                <?php foreach ($verifiedKycSubmissions as $kyc): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($kyc['id']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['father_name']); ?></td>
                        <td><?php echo htmlspecialchars($kyc['mother_name']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['residential_proof_path']); ?>" target="_blank">View</a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['id_proof_path']); ?>" target="_blank">View</a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($kyc['selfie_path']); ?>" target="_blank">View</a>
                        </td>
                        <td><?php echo htmlspecialchars($kyc['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No verified KYC submissions.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php include '../include/footer.php';?>
