<?php
  require 'config/config.php';
  $data = [];
  
  if(isset($_POST['search'])) {
    // Get data from FORM
    $keywords = $_POST['keywords'];
    $location = $_POST['location'];

    // keywords based search
    $keyword = explode(',', $keywords);
    $concats = [];
    foreach ($keyword as $key => $value) {
      $concats[] = "%" . trim($value) . "%";
    }

    // location based search
    $locations = explode(',', $location);
    $loc = [];
    foreach ($locations as $key => $value) {
      $loc[] = "%" . trim($value) . "%";
    }

    try {
      // Prepare the SQL query with placeholders
      $stmt = $connect->prepare("
        SELECT * FROM room_rental_registrations_apartment 
        WHERE 
          (country LIKE :keyword OR state LIKE :keyword OR city LIKE :keyword OR address LIKE :keyword OR rooms LIKE :keyword OR landmark LIKE :keyword OR rent LIKE :keyword OR deposit LIKE :keyword)
          AND 
          (country LIKE :location OR state LIKE :location OR city LIKE :location OR address LIKE :location OR landmark LIKE :location)
      ");

      // Bind parameters for keyword and location search
      $stmt->bindParam(':keyword', $concats[0], PDO::PARAM_STR);
      $stmt->bindParam(':location', $loc[0], PDO::PARAM_STR);

      // Execute the query
      $stmt->execute();
      $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Same for other query
      $stmt = $connect->prepare("
        SELECT * FROM room_rental_registrations 
        WHERE 
          (country LIKE :keyword OR state LIKE :keyword OR city LIKE :keyword OR address LIKE :keyword OR rooms LIKE :keyword OR landmark LIKE :keyword OR rent LIKE :keyword OR deposit LIKE :keyword)
          AND 
          (country LIKE :location OR state LIKE :location OR city LIKE :location OR address LIKE :location OR landmark LIKE :location)
      ");

      $stmt->bindParam(':keyword', $concats[0], PDO::PARAM_STR);
      $stmt->bindParam(':location', $loc[0], PDO::PARAM_STR);

      // Execute the second query
      $stmt->execute();
      $data8 = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Merge results
      $data = array_merge($data2, $data8);

    } catch(PDOException $e) {
      $errMsg = $e->getMessage();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>App</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="assets/css/rent.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
     /* Button Container Styles */
     .button-container {
    display: flex;
    justify-content: center;
    gap: 20px; /* Space between buttons */
    margin-top: 20px; /* Gap from content above */
}

/* General Button Styles */
button {
    display: inline-block;
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    font-size: 16px;
    width: 180px; /* Equal size for all buttons */
    height: 50px;
    border: none;
    border-radius: 8px; /* Slightly rounded corners */
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease-in-out;
}

/* Book Now Button */
button.btn-primary {
    background: linear-gradient(135deg, #007bff, #00aaff); /* Blue gradient */
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
}

button.btn-primary:hover {
    background: linear-gradient(135deg, #00aaff, #007bff); /* Inverted blue gradient */
    transform: translateY(-3px); /* Hover lift effect */
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.6);
}

/* Save for Later Button */
button.btn-secondary {
    background: linear-gradient(135deg, #66ccff, #3399ff); /* Lighter blue gradient */
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(102, 204, 255, 0.4);
}

button.btn-secondary:hover {
    background: linear-gradient(135deg, #3399ff, #66ccff); /* Inverted lighter blue gradient */
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(102, 204, 255, 0.6);
}

/* Equal Alignment */
button:focus {
    outline: none;
}


    </style>
  </head>

  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Logo/Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
           
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#search">Search</a>
            </li>
            
            <?php 
              if(empty($_SESSION['username'])){
                echo '<li class="nav-item">';
                  echo '<a class="nav-link" href="./auth/login.php">Login</a>';
                echo '</li>';
              }else{
                echo '<li class="nav-item">';
                 echo '<a class="nav-link" href="./auth/dashboard.php">Home</a>';
               echo '</li>';
              }
            ?>
            

            <li class="nav-item">
              <a class="nav-link" href="./auth/register.php">Register</a>
            </li>

          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="masthead">
      <div class="container">
        <div class="intro-text">
          <div class="intro-lead-in">Welcome To Room Rental Registration!</div>
          <div class="intro-heading text-uppercase">It's Nice To See You<br></div>
        </div>
      </div>
    </header>

     <!-- Search -->
    <section id="search">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Search</h2>
            <h3 class="section-subheading text-muted">Search rooms or homes for hire.</h3>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form action="" method="POST" class="center" novalidate>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" id="keywords" name="keywords" type="text" placeholder="Key words(Ex: 1bhk,rent..)" required data-validation-required-message="Please enter keywords">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <input class="form-control" id="location" type="text" name="location" placeholder="Location" required data-validation-required-message="Please enter location.">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>         

                <div class="col-md-2">
                  <div class="form-group">
                    <button id="" class="btn btn-success btn-md text-uppercase" name="search" value="search" type="submit">Search</button>
                  </div>
                </div>
              </div>
            </form>

            <?php
              if(isset($errMsg)){
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
              }
              if(count($data) !== 0){
                echo "<h2 class='text-center'>List of Apartment Details</h2>";
              }else{
                echo "<h2 class='text-center' style='color:red;'>Try Some other keywords</h2>";
              }
            ?>        
            <?php 
foreach ($data as $key => $value) {           
  echo '<div class="card card-inverse card-info mb-3" style="padding:1%;">          
        <div class="card-block">';
         echo '<div class="row">
            <div class="col-4">
              <h4 class="text-center">Owner Details</h4>';
              echo '<p><b>Owner Name: </b>'.$value['fullname'].'</p>';
              echo '<p><b>Mobile Number: </b>'.$value['mobile'].'</p>';
              echo '<p><b>Alternate Number: </b>'.$value['alternat_mobile'].'</p>';
              echo '<p><b>Email: </b>'.$value['email'].'</p>';
              echo '<p><b>Country: </b>'.$value['country'].'</p><p><b> State: </b>'.$value['state'].'</p><p><b> City: </b>'.$value['city'].'</p>';
              if ($value['image'] !== 'uploads/') {
                echo '<img src="app/'.$value['image'].'" width="100">';
              }

          echo '</div>
            <div class="col-5">
              <h4 class="text-center">Room Details</h4>';
              echo '<p><b>Plot Number: </b>'.$value['plot_number'].'</p>';

              if(isset($value['sale'])){
                echo '<p><b>Sale: </b>'.$value['sale'].'</p>';
              } 
              
              if(isset($value['apartment_name']))                         
                echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>'.$value['apartment_name'].'</p></div>';

              if(isset($value['ap_number_of_plats']))
                echo '<div class="alert alert-success" role="alert"><p><b>Plat Number: </b>'.$value['ap_number_of_plats'].'</p></div>';

              echo '<p><b>Available Rooms: </b>'.$value['rooms'].'</p>';
              echo '<p><b>Address: </b>'.$value['address'].'</p><p><b> Landmark: </b>'.$value['landmark'].'</p>';
          echo '</div>
            <div class="col-3">
              <h4>Other Details</h4>';
              echo '<p><b>Accommodation: </b>'.$value['accommodation'].'</p>';
              echo '<p><b>Description: </b>'.$value['description'].'</p>';
              if($value['vacant'] == 0){ 
                echo '<div class="alert alert-danger" role="alert"><p><b>Occupied</b></p></div>';
              } else {
                echo '<div class="alert alert-success" role="alert"><p><b>Vacant</b></p></div>';
              } 

              // Add the buttons
              echo '<div class="text-center mt-3">
              <button class="btn btn-primary" onclick="bookNow()">Book Now</button>
              <button class="btn btn-secondary" onclick="saveForLater()">Save for Later</button>
            </div>
            ';
          echo '</div>
        </div>              
      </div>';
}
?>
   
          </div>
        </div>
      </div>
      <br><br><br><br><br><br>
    </section>    

    <!-- Footer -->
    <footer style="background-color: #ccc;">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <span class="copyright">Copyright &copy; Your Website 2018</span>
          </div>
          <div class="col-md-4">
            <ul class="list-inline social-buttons">
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-linkedin"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
   
    <!-- Bootstrap core JavaScript -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="assets/plugins/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="assets/js/jqBootstrapValidation.js"></script>
    <script src="assets/js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="assets/js/rent.js"></script>
  </body>
</html>
