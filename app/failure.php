<?php
require '../config/config.php';

// Check if we have the success query parameter
if (isset($_GET['q']) && $_GET['q'] == 'fu') {
    $registration_id = $_GET['pid']; // Get the registration ID from the query string

    try {
        // Database connection
        $pdo = new PDO('mysql:host=localhost;dbname=newrent', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the registration details to ensure it exists
        $query = "SELECT * FROM room_rental_registrations WHERE id = :registration_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':registration_id', $registration_id, PDO::PARAM_INT);
        $stmt->execute();
        $registration = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$registration) {
            $_SESSION['error'] = "Registration not found.";
            header('Location: payment.php');
            exit();
        }

        // Update the payment status to 'completed'
        $update_query = "UPDATE room_rental_registrations SET payment_status = 'completed' WHERE id = :registration_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':registration_id', $registration_id, PDO::PARAM_INT);
        $stmt->execute();

        // Provide a success message and redirect the user
        $_SESSION['message'] = "Payment was successful. Registration completed.";
        header('Location: payment.php');
        exit();

    } catch (Exception $e) {
        // Error handling
        $_SESSION['error'] = "Error updating payment status: " . $e->getMessage();
        header('Location: payment.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: payment.php');
    exit();
}
?>
