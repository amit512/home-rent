<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');

	if($_SESSION['role'] == 'admin'){
		$stmt = $connect->prepare('SELECT count(*) as register_user FROM users');
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_ASSOC);


		$stmt = $connect->prepare('SELECT count(*) as total_rent FROM room_rental_registrations');
		$stmt->execute();
		$total_rent = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = $connect->prepare('SELECT count(*) as total_rent_apartment FROM room_rental_registrations_apartment');
		$stmt->execute();
		$total_rent_apartment = $stmt->fetch(PDO::FETCH_ASSOC);
	}

	$stmt = $connect->prepare('SELECT count(*) as total_auth_user_rent FROM room_rental_registrations WHERE user_id = :user_id');
	$stmt->execute(array(
		':user_id' => $_SESSION['id']
		));
	$total_auth_user_rent = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = $connect->prepare('SELECT count(*) as total_auth_user_rent_ap FROM room_rental_registrations_apartment WHERE user_id = :user_id');
	$stmt->execute(array(
		':user_id' => $_SESSION['id']
		));
	$total_auth_user_rent_ap = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
	<section class="wrapper" style="margin-left: 16%;margin-top: -11%;">
		 <div class="container">
			 <div class="row">
				<div class="col-md-12">
					<h1>Dash board</h1>
					<div class="row">						
						<?php 
							if($_SESSION['role'] == 'admin'){ 
								echo '<div class="col-md-3">';
								echo '<a href="../app/users.php"><div class="alert alert-warning" role="alert">';
								echo '<b>Registered Users: <span class="badge badge-pill badge-success">'.$count['register_user'].'</span></b>';
								echo '</div></a>';
								echo '</div>';
							} 
						?>	
						<?php 
							if($_SESSION['role'] == 'admin'){ 
								echo '<div class="col-md-3">';
								echo '<a href="../app/list.php"><div class="alert alert-warning" role="alert">';
								echo '<b>Rooms for Rent: <span class="badge badge-pill badge-success">'.(intval($total_rent['total_rent'])+intval($total_rent_apartment['total_rent_apartment'])).'</span></b>';
								echo '</div></a>';
								echo '</div>';
							} 
						?>
						<?php 
							if($_SESSION['role'] == 'user'){ 
								echo '<div class="col-md-3">';
								echo '<a href="../app/list.php"><div class="alert alert-warning" role="alert">';
								echo '<b>Registered Rooms: <span class="badge badge-pill badge-success">'.$total_auth_user_rent['total_auth_user_rent'].'</span></b>';
								echo '</div></a>';
								echo '</div>';
							} 
						?>
						<?php

// Fetch Payment Status Data for rooms (Paid vs Unpaid)
$stmt = $connect->prepare("
    SELECT payment_status, COUNT(*) AS count
    FROM room_rental_registrations
    GROUP BY payment_status
");
$stmt->execute();
$paymentStatusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize counts for paid and unpaid rooms
$counts = [
    'paid' => 0,
    'unpaid' => 0
];

// Fill counts with actual data from the database (for rooms)
foreach ($paymentStatusData as $row) {
    if ($row['payment_status'] == 'completed') {
        $counts['paid'] += $row['count'];
    } else {
        $counts['unpaid'] += $row['count'];
    }
}

// Fetch Payment Status Data for apartments (Paid vs Unpaid)
$stmt = $connect->prepare("
    SELECT payment_status, COUNT(*) AS count
    FROM room_rental_registrations_apartment
    GROUP BY payment_status
");
$stmt->execute();
$apartmentPaymentStatusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize counts for paid and unpaid apartments
$apartmentCounts = [
    'paid' => 0,
    'unpaid' => 0
];

// Fill counts with actual data from the database (for apartments)
foreach ($apartmentPaymentStatusData as $row) {
    if ($row['payment_status'] == 'completed') {
        $apartmentCounts['paid'] += $row['count'];
    } else {
        $apartmentCounts['unpaid'] += $row['count'];
    }
}

// Prepare data for the paid/unpaid graph (Rooms vs Apartments)
$labels = ['Rooms', 'Apartments'];
$paidData = [$counts['paid'], $apartmentCounts['paid']];
$unpaidData = [$counts['unpaid'], $apartmentCounts['unpaid']];

// Fetch Monthly Revenue Data for Rooms
$stmt = $connect->prepare("
    SELECT MONTH(created_at) AS month, SUM(rent) AS revenue
    FROM room_rental_registrations
    WHERE payment_status = 'completed'
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)
");
$stmt->execute();
$monthlyRevenueDataRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Monthly Revenue Data for Apartments
$stmt = $connect->prepare("
    SELECT MONTH(created_at) AS month, SUM(rent) AS revenue
    FROM room_rental_registrations_apartment
    WHERE payment_status = 'completed'
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)
");
$stmt->execute();
$monthlyRevenueDataApartments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine both room and apartment monthly revenue data
$months = [];
$revenueRooms = [];
$revenueApartments = [];

foreach ($monthlyRevenueDataRooms as $row) {
    $months[] = $row['month'];
    $revenueRooms[] = (float)$row['revenue'];
}

foreach ($monthlyRevenueDataApartments as $row) {
    if (!in_array($row['month'], $months)) {
        $months[] = $row['month'];
    }
    $revenueApartments[] = (float)$row['revenue'];
}
// Calculate Total Income for Rooms (10% of Sales)
$stmt = $connect->prepare("
    SELECT SUM(sale) AS total_sales
    FROM room_rental_registrations
    WHERE payment_status = 'completed'
");
$stmt->execute();
$totalSalesData = $stmt->fetch(PDO::FETCH_ASSOC);
$totalSales = $totalSalesData['total_sales'] ?? 0; 
$totalIncomeFromSales = $totalSales * 0.1;

// Calculate Total Income for Apartments (10% of Rent)
$stmt = $connect->prepare("
    SELECT SUM(rent) AS total_rent_apartment
    FROM room_rental_registrations_apartment
    WHERE payment_status = 'completed'
");
$stmt->execute();
$totalRentApartmentData = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRentApartment = $totalRentApartmentData['total_rent_apartment'] ?? 0; 
$totalIncomeFromRentApartment = $totalRentApartment * 0.1; 

// Final Total Income (sum of both incomes)
$totalIncome = $totalIncomeFromSales + $totalIncomeFromRentApartment;
?>

<?php if ($_SESSION['role'] == 'admin') { ?>

<div class="col-md-12">
    <h2>Payment Status Overview</h2>
    <div class="chart-container">
       
        <div style="width: 48%; height: 300px; display: inline-block;">
            <canvas id="paymentStatusChart" width="100" height="50"></canvas>
        </div>
      
        <div style="width: 48%; height: 300px; display: inline-block;">
            <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div style="margin-top: 40px; font-size: 18px;">
        <strong>Total Income (Rooms) : </strong>
        <span style="font-size: 20px; color: green;">Rs.<?= number_format($totalIncomeFromSales, 2) ?></span><br>
        <strong>Total Income (Apartments) : </strong>
        <span style="font-size: 20px; color: green;">Rs.<?= number_format($totalIncomeFromRentApartment, 2) ?></span><br>
        <strong>Total Income : </strong>
        <span style="font-size: 24px; color: green;">Rs.<?= number_format($totalIncome, 2) ?></span>
    </div>
</div>

<input type="hidden" id="labels" value='<?= json_encode($labels) ?>'>
<input type="hidden" id="paidData" value='<?= json_encode($paidData) ?>'>
<input type="hidden" id="unpaidData" value='<?= json_encode($unpaidData) ?>'>
<input type="hidden" id="monthlyLabels" value='<?= json_encode($months) ?>'>
<input type="hidden" id="monthlyRevenueRooms" value='<?= json_encode($revenueRooms) ?>'>
<input type="hidden" id="monthlyRevenueApartments" value='<?= json_encode($revenueApartments) ?>'>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = JSON.parse(document.getElementById('labels').value);
    const paidData = JSON.parse(document.getElementById('paidData').value);
    const unpaidData = JSON.parse(document.getElementById('unpaidData').value);

    const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    new Chart(paymentStatusCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Paid',
                data: paidData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Unpaid',
                data: unpaidData,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Monthly Revenue Chart (Rooms vs Apartments)
    const monthlyLabels = JSON.parse(document.getElementById('monthlyLabels').value);
    const monthlyRevenueRooms = JSON.parse(document.getElementById('monthlyRevenueRooms').value);
    const monthlyRevenueApartments = JSON.parse(document.getElementById('monthlyRevenueApartments').value);

    const revenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Room Revenue',
                data: monthlyRevenueRooms,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }, {
                label: 'Apartment Revenue',
                data: monthlyRevenueApartments,
                fill: false,
                borderColor: 'rgba(153, 102, 255, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<style>
    .chart-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }
    .chart-container > div {
        width: 48%;
    }
</style>
<?php } ?>

</div>
				</div>
			</div> 
		 </div>
	</section>
<?php include '../include/footer.php';?>