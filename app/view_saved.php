<?php
require '../config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo '<p class="alert alert-danger">Please log in to view your saved items.</p>';
    exit;
}

// Fetch saved items for the logged-in user
try {
    $user_id = $_SESSION['id'];

    $stmt = $connect->prepare("
        SELECT si.item_id, si.id AS saved_item_id, 
               rrr.fullname AS room_fullname, rrr.mobile AS room_mobile, rrr.email AS room_email, rrr.city AS room_city, 
               rrr.image AS room_image, rrr.plot_number AS room_plot_number, rrr.rooms AS room_rooms, rrr.description AS room_description, rrr.vacant AS room_vacant,
               rrra.fullname AS apartment_fullname, rrra.mobile AS apartment_mobile, rrra.email AS apartment_email, rrra.city AS apartment_city, 
               rrra.image AS apartment_image, rrra.plot_number AS apartment_plot_number, rrra.apartment_name, rrra.rooms AS apartment_rooms, 
               rrra.description AS apartment_description, rrra.vacant AS apartment_vacant
        FROM saved_items si
        LEFT JOIN room_rental_registrations rrr ON si.item_id = rrr.id
        LEFT JOIN room_rental_registrations_apartment rrra ON si.item_id = rrra.id
        WHERE si.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $saved_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($saved_items)) {
        echo '<p class="alert alert-info">You have no saved items.</p>';
        exit;
    }
} catch (Exception $e) {
    echo '<p class="alert alert-danger">Error fetching saved items: ' . $e->getMessage() . '</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Saved Items - UrbanDwells</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/rent.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            margin-left: 250px; /* Adjust width for side navigation */
        }

        .side-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
        }

        .side-nav a {
            color: #ddd;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .side-nav a:hover {
            background-color: #495057;
            color: #fff;
        }

        .saved-item-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px 0;
        }

        .saved-item-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .saved-item-card:hover {
            transform: translateY(-5px);
        }

        .saved-item-card h4 {
            color: #007bff;
        }

        /* Fullscreen modal for image */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-overlay img {
            max-width: 90%;
            max-height: 90%;
            border: 2px solid #fff;
        }

        .modal-overlay.show {
            display: flex;
        }
    </style>
</head>
<body>
    <?php include '../include/header.php'; ?>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:#212529;" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="../index.php">UrbanDwells</a>
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
    <section style="padding-left:0px;">
        <?php include '../include/side-nav.php'; ?>
        <section class="wrapper" style="margin-left: 16%;margin-top: -10%;">
            <div class="container">
                <h2 class="text-center my-4">Your Saved Items</h2>
                <div class="saved-item-container">
                    <?php foreach ($saved_items as $item): ?>
                        <div class="saved-item-card">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Owner Details</h4>
                                    <p><b>Name:</b> <?= htmlspecialchars($item['room_fullname'] ?? $item['apartment_fullname']) ?></p>
                                    <p><b>Mobile:</b> <?= htmlspecialchars($item['room_mobile'] ?? $item['apartment_mobile']) ?></p>
                                    <p><b>Email:</b> <?= htmlspecialchars($item['room_email'] ?? $item['apartment_email']) ?></p>
                                    <p><b>City:</b> <?= htmlspecialchars($item['room_city'] ?? $item['apartment_city']) ?></p>
                                    <?php 
                                    $image = $item['room_image'] ?? $item['apartment_image'];
                                    $imagePath = str_replace('\\', '/', $image);
                                    if (!empty($image) && $image !== '../app/uploads/' && file_exists($imagePath)): ?>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" class="thumbnail" width="100" alt="Image">
                                    <?php else: ?>
                                        <p>No image available</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-5">
                                    <h4>Details</h4>
                                    <p><b>Plot Number:</b> <?= htmlspecialchars($item['room_plot_number'] ?? $item['apartment_plot_number']) ?></p>
                                    <?php if (!empty($item['apartment_name'])): ?>
                                        <p><b>Apartment Name:</b> <?= htmlspecialchars($item['apartment_name']) ?></p>
                                    <?php endif; ?>
                                    <p><b>Available Rooms:</b> <?= htmlspecialchars($item['room_rooms'] ?? $item['apartment_rooms']) ?></p>
                                </div>
                                <div class="col-md-3">
                                    <h4>Other Details</h4>
                                    <p><b>Description:</b> <?= htmlspecialchars($item['room_description'] ?? $item['apartment_description']) ?></p>
                                    <p><b>Status:</b> 
                                        <?php if (($item['room_vacant'] ?? $item['apartment_vacant']) == 1): ?>
                                            <span class="alert alert-success">Vacant</span>
                                        <?php else: ?>
                                            <span class="alert alert-danger">Occupied</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </section>

    <!-- Modal for Fullscreen Image -->
    <div class="modal-overlay" id="imageModal">
        <img src="" alt="Fullscreen Image" id="modalImage">
    </div>

    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle image click for fullscreen modal
        document.querySelectorAll('.thumbnail').forEach(img => {
            img.addEventListener('click', (e) => {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = e.target.src;
                modal.classList.add('show');
            });
        });

        // Close the modal when clicked outside the image
        document.getElementById('imageModal').addEventListener('click', () => {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
        });
    </script>
    <footer><?php include '../include/footer.php'; ?></footer>
</body>
</html>
