<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');

	try {
		if($_SESSION['role'] == 'admin'){
			$stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment');
			$stmt->execute();
			$data1 = $stmt->fetchAll (PDO::FETCH_ASSOC);

			$stmt = $connect->prepare('SELECT * FROM room_rental_registrations');
			$stmt->execute();
			$data2 = $stmt->fetchAll (PDO::FETCH_ASSOC);

			$data = array_merge($data1,$data2);
		}

		if($_SESSION['role'] == 'user'){
			$stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment WHERE :user_id = user_id ');
			$stmt->execute(array(
				':user_id' => $_SESSION['id']
			));
			$data1 = $stmt->fetchAll (PDO::FETCH_ASSOC);

			$stmt = $connect->prepare('SELECT * FROM room_rental_registrations WHERE :user_id = user_id ');
			$stmt->execute(array(
				':user_id' => $_SESSION['id']
			));
			$data2 = $stmt->fetchAll (PDO::FETCH_ASSOC);

			$data = array_merge($data1,$data2);
		}
	}catch(PDOException $e) {
		$errMsg = $e->getMessage();
	} 
?>
<?php include '../include/header.php';?>

<!-- Header nav -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:#212529;" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="../index.php">UrbanDwells</a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarResponsive"
      aria-controls="navbarResponsive"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if ($_SESSION['role'] == 'admin') { echo "(Admin)"; } ?></a>
        </li>
        <li class="nav-item">
          <a href="../auth/logout.php" class="nav-link">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- end header nav -->

<section style="padding-left:0px;">
	<?php include '../include/side-nav.php';?>
</section>

<section class="wrapper" style="margin-left: 16%;margin-top: -23%;">
	<div class="container">
		<div class="row">
			<div class="col-12">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
				}
			?>
			<h2>List of Apartment Details</h2>
				<?php 
					foreach ($data as $key => $value) { 						
						echo '<div class="card card-inverse card-info mb-3" style="padding:1%;"> 						
							  <div class="card-block">';

							   echo '<a class="btn btn-warning float-right" href="update.php?id='.$value['id'].'&act=';if(!empty($value['own'])){ echo "ap"; }else{ echo "indi"; } echo '">Edit</a>';
								 echo 	'<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
									<h4 class="text-center">Owner Details</h4>';
									  echo '<p><b>Owner Name: </b>'.$value['fullname'].'</p>';
									  echo '<p><b>Mobile Number: </b>'.$value['mobile'].'</p>';
									  echo '<p><b>Alternate Number: </b>'.$value['alternat_mobile'].'</p>';
									  echo '<p><b>Email: </b>'.$value['email'].'</p>';
									  echo '<p><b>Country: </b>'.$value['country'].'</p><p><b> State: </b>'.$value['state'].'</p><p><b> City: </b>'.$value['city'].'</p>';
									  if ($value['image'] !== 'uploads/') {
									  	echo '<img src="'.$value['image'].'" class="thumbnail" onclick="openFullscreen(this)" style="cursor: pointer; max-width: 100%; height: auto;">'; 
									  }

									  echo '<p><b>Address: </b>'.$value['address'].'</p><p><b> Landmark: </b>'.$value['landmark'].'</p>';

								echo '</div>
									<div class="col-lg-5 col-md-6 col-sm-12">
									<h4 class="text-center">Room Details</h4>';
										if(isset($value['sale'])){
											echo '<p><b>Sale: </b>'.$value['sale'].'</p>';
										} 							

											if(isset($value['apartment_name']))
												echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>'.$value['apartment_name'].'</p></div>';

											if(isset($value['ap_number_of_plats']))
												echo '<div class="alert alert-success" role="alert"><p><b>Flat Number: </b>'.$value['ap_number_of_plats'].'</p></div>';
										if(isset($value['own'])){
											echo '<p><b>Available Area: </b>'.$value['area'].'</p>';
											echo '<p><b>Floor: </b>'.$value['floor'].'</p>';
											echo '<p><b>Owner/Rented: </b>'.$value['own'].'</p>';
											echo '<p><b>Purpose: </b>'.$value['purpose'].'</p>';
										}
										echo '<p><b>Available Rooms: </b>'.$value['rooms'].'</p>';
										echo '</div>
									<div class="col-lg-3 col-md-12 col-sm-12">
									<h4>Other Details</h4>';
									echo '<p><b>Accommodation: </b>'.$value['accommodation'].'</p>';
									echo '<p><b>Description: </b>'.$value['description'].'</p>';
										if($value['vacant'] == 0){ 
											echo '<div class="alert alert-danger" role="alert"><p><b>Occupied</b></p></div>';
										}else{
											echo '<div class="alert alert-success" role="alert"><p><b>Vacant</b></p></div>';
										} 
									echo '</div>
								</div> 					      
						   </div>
						</div>';
					}
				?>				
			</div>
		</div>
	</div> 
</section>

<!-- Fullscreen Modal -->
<div id="fullscreenModal" class="modal" onclick="closeFullscreen()">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
</div>

<!-- JavaScript for Fullscreen Image -->
<script>
  function openFullscreen(img) {
    var modal = document.getElementById("fullscreenModal");
    var modalImg = document.getElementById("img01");
    modal.style.display = "flex"; // Use flexbox to center the image
    modalImg.src = img.src;
  }

  function closeFullscreen() {
    var modal = document.getElementById("fullscreenModal");
    modal.style.display = "none";
  }
</script>

<style>
  /* Fullscreen Modal */
#fullscreenModal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 60px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.9);
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.modal-content {
  max-width: 90%;
  max-height: 80%;
  object-fit: contain; /* Keeps the image proportional */
}

.close {
  position: absolute;
  top: 10px;
  right: 20px;
  color: #fff;
  font-size: 30px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* Responsive Cards */
.card {
  margin-bottom: 1rem;
}

/* Navbar */
.navbar-toggler {
  margin-top: 10px;
}
.navbar {
  display: flex;
  flex-wrap: nowrap;
  justify-content: space-between;
  align-items: center;
}

/* For Small Screen Devices */
@media (max-width: 768px) {
  .navbar-brand {
    font-size: 18px;
  }
  .navbar-toggler {
    margin-top: 0;
  }
  .navbar-collapse {
    text-align: center;
  }
  .wrapper {
    margin-left: 0;
    margin-top: 0;
    padding: 1rem;
  }

  /* Ensure cards stack vertically on smaller screens */
  .card {
    width: 100%;
  }

  .col-lg-4, .col-lg-5, .col-lg-3 {
    margin-bottom: 15px; /* Add some space between columns */
  }
}

@media (max-width: 480px) {
  .navbar {
    flex-wrap: wrap;
  }

  .navbar-toggler {
    margin-bottom: 10px;
  }

  .modal-content {
    max-width: 95%;
    max-height: 60%;
  }

  .wrapper {
    padding: 0;
    margin-left: 0;
    margin-top: 10px;
  }
}

</style>

<?php include '../include/footer.php';?>
