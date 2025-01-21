<?php
require '../config/config.php';
  // Ensure session is started

// Redirect if the user is not logged in

if (empty($_SESSION['username']) ) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['id'];
// Database connection
// Fetch room rental registration details for the user from the database
$pdo = new PDO('mysql:host=localhost;dbname=newrent', 'root', ''); // Adjust your DB credentials
$query = "SELECT * FROM room_rental_registrations WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch all registrations
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);


include '../include/header.php';
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<link href="assets/css/style.css" rel="stylesheet">
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
          <a href="../auth/logout.php" class="nav-link">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php include '../include/side-nav.php'; ?>

<section class="wrapper" style="margin-left: 16%; margin-top: -11%;">
    <h2>Room Rental Registration Status</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Registration ID</th>
                <th>Full Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Rent</th>
                <th>Deposit</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th> <!-- Added Action column for Payment Button -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($registrations)): ?>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><?php echo $registration['id']; ?></td>
                        <td><?php echo $registration['fullname']; ?></td>
                        <td><?php echo $registration['mobile']; ?></td>
                        <td><?php echo $registration['email']; ?></td>
                        <td><?php echo $registration['sale']; ?></td>
                        <td><?php echo $registration['deposit']; ?></td>
                        <td>
                            <?php if ($registration['payment_status'] === 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php elseif ($registration['payment_status'] === 'completed'): ?>
                                <span class="badge badge-success">Completed</span>
                            <?php elseif ($registration['payment_status'] === 'failed'): ?>
                                <span class="badge badge-danger">Failed</span>
                            <?php elseif ($registration['payment_status'] === 'refunded'): ?>
                                <span class="badge badge-secondary">Refunded</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $registration['created_at']; ?></td>
                        <td><?php echo $registration['updated_at']; ?></td>
                        <td>
<!-- Process Payment Button -->
<?php if ($registration['payment_status'] === 'pending'): ?>
    <form action="process_payment.php" method="POST">
        <input type="hidden" name="registration_id" value="<?php echo $registration['id']; ?>">
        <button type="submit" class="btn btn-light-blue btn-sm">Process Payment</button> <!-- Custom Button -->
    </form>
<?php elseif ($registration['payment_status'] === 'completed'): ?>
    <button class="btn btn-success btn-sm" disabled>Payment Completed</button>
<?php elseif ($registration['payment_status'] === 'failed'): ?>
    <button class="btn btn-danger btn-sm" disabled>Payment Failed</button>
<?php elseif ($registration['payment_status'] === 'refunded'): ?>
    <button class="btn btn-secondary btn-sm" disabled>Refunded</button>
<?php endif; ?>


                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No registrations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php include '../include/footer.php'; ?>
<style>
/* Custom button styles for light blue */
.btn-light-blue {
    background-color:rgb(79, 186, 222); /* Light Blue */
    color: white;
    border: none;
}

.btn-light-blue:hover {
    background-color: #ffeb3b; /* Yellow on hover */
    color: black; /* Change text color to black on hover */
}


</style>
