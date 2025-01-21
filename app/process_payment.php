<?php
require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_id = $_POST['registration_id'] ?? null;

    if (!$registration_id) {
        $_SESSION['error'] = "Invalid request.";
        header('Location: payment.php');
        exit();
    }

    try {
        // Database connection
        $pdo = new PDO('mysql:host=localhost;dbname=newrent', 'root', ''); // Adjust your DB credentials
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch registration details for the provided registration_id
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

        // Simulate successful payment (for demonstration purposes)
        // In a real scenario, this would be where the actual payment gateway logic is implemented
        $payment_successful = true; // Change to false to simulate a failed payment

        if ($payment_successful) {
            // Update payment status to 'completed'
            $update_query = "UPDATE room_rental_registrations SET payment_status = 'completed' WHERE id = :registration_id";
            $stmt = $pdo->prepare($update_query);
            $stmt->bindParam(':registration_id', $registration_id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = "Payment successful. Registration completed.";
            header('Location: payment.php');
            exit();
        } else {
            // Simulate failed payment
            $_SESSION['error'] = "Payment failed. Please try again.";
            header('Location: payment.php');
            exit();
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error occurred: " . $e->getMessage();
        header('Location: payment.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: payment.php');
    exit();
}
