<?php
// Start the session and include the database configuration
require '../config/config.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate if 'item_id' exists in the request
    if (isset($data['item_id'])) {
        $item_id = $data['item_id'];

        // Check if the user is logged in
        if (!isset($_SESSION['id'])) {
            echo json_encode(['success' => false, 'error' => 'User not logged in']);
            exit;
        }

        $user_id = $_SESSION['id'];

        try {
            // Check if the item is already saved for the user
            $checkStmt = $connect->prepare("SELECT COUNT(*) FROM saved_items WHERE user_id = :user_id AND item_id = :item_id");
            $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $checkStmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $checkStmt->execute();
            $itemExists = $checkStmt->fetchColumn();

            if ($itemExists > 0) {
                echo json_encode(['success' => false, 'error' => 'Item is already saved']);
                exit;
            }

            // Insert the item into saved_items table
            $stmt = $connect->prepare("INSERT INTO saved_items (user_id, item_id) VALUES (:user_id, :item_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $stmt->execute();

            // Return success response
            echo json_encode(['success' => true, 'message' => 'Item saved successfully']);
        } catch (PDOException $e) {
            // Check for duplicate entry error
            if ($e->getCode() === '23000') { // SQLSTATE[23000]: Integrity constraint violation
                echo json_encode(['success' => false, 'error' => 'Item is already saved']);
            } else {
                // Return other database errors
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
        }
    } else {
        // Return error if 'item_id' is missing
        echo json_encode(['success' => false, 'error' => 'Item ID is required']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>


