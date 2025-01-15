<?php
// Start session

// Include the database connection file
require '../config/config.php';  // Ensure the $connect PDO instance is available

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to submit KYC.");
}

// Check if mobile number is available in the session
if (!isset($_SESSION['mobile'])) {
    die("Mobile number is not set. Please log in again.");
}

// Get the logged-in user's ID and mobile number
$userId = $_SESSION['id'];
$userMobile = $_SESSION['mobile'];  // Assuming mobile is stored in the session
$userName = $_SESSION['fullname'];
// Check if KYC is already submitted for this mobile number using PDO
$checkStmt = $connect->prepare("SELECT * FROM kyc_verifications WHERE mobile = ?");
$checkStmt->execute([$userMobile]);
$kyc = $checkStmt->fetch(PDO::FETCH_ASSOC);

// If KYC is found, check the status
if ($kyc) {
    if ($kyc['status'] == 'Verified') {
        // If KYC is verified, redirect to the profile page
        header("Location: dashboard.php");  // Assuming the profile page is profile.php
        exit;
    } else {
        // If KYC is under review, show "under review" message
        echo "KYC is under review. Please wait for the verification process.";
        exit;
    }
}

// Function to handle file uploads
function uploadFile($file, $uploadDir) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // Allowed file types
    $maxFileSize = 5 * 1024 * 1024; // 5MB limit
    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $targetFile = $uploadDir . uniqid() . '.' . $fileExtension; // Generate unique file name

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ["success" => false, "error" => "Error uploading file."];
    }

    if (!in_array($fileExtension, $allowedExtensions)) {
        return ["success" => false, "error" => "Invalid file type. Allowed: " . implode(", ", $allowedExtensions)];
    }

    if ($file['size'] > $maxFileSize) {
        return ["success" => false, "error" => "File size exceeds 5MB limit."];
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["success" => true, "path" => $targetFile];
    } else {
        return ["success" => false, "error" => "Failed to save the file."];
    }
}

// Handle KYC submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use PDO to handle user input
    $fatherName = htmlspecialchars($_POST['father_name']);
    $motherName = htmlspecialchars($_POST['mother_name']);
    $residentialProof = $_FILES['residential_proof'];
    $idProof = $_FILES['id_proof'];
    $selfie = $_FILES['selfie'];

    $uploadDir = "uploads/kyc/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file uploads
    $residentialProofUpload = uploadFile($residentialProof, $uploadDir);
    $idProofUpload = uploadFile($idProof, $uploadDir);
    $selfieUpload = uploadFile($selfie, $uploadDir);

    // Check if all files were uploaded successfully
    if (!$residentialProofUpload['success']) {
        die("Residential Proof Upload Error: " . $residentialProofUpload['error']);
    }
    if (!$idProofUpload['success']) {
        die("ID Proof Upload Error: " . $idProofUpload['error']);
    }
    if (!$selfieUpload['success']) {
        die("Selfie Upload Error: " . $selfieUpload['error']);
    }

    $residentialProofPath = $residentialProofUpload['path'];
    $idProofPath = $idProofUpload['path'];
    $selfiePath = $selfieUpload['path'];

    // Insert new KYC record using PDO
    $stmt = $connect->prepare("INSERT INTO kyc_verifications (user_name, mobile, father_name, mother_name, residential_proof_path, id_proof_path, selfie_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $status = 'Pending'; // KYC status initially is 'Pending'
    $stmt->execute([ $userName, $userMobile, $fatherName, $motherName, $residentialProofPath, $idProofPath, $selfiePath, $status]);

    echo "KYC submitted successfully. Waiting for verification.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>KYC Verification</title>
</head>
<body>
    <h1>KYC Verification</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="father_name">Father's Name:</label>
        <input type="text" name="father_name" id="father_name" required><br><br>

        <label for="mother_name">Mother's Name:</label>
        <input type="text" name="mother_name" id="mother_name" required><br><br>

        <label for="residential_proof">Upload Residential Proof:</label>
        <input type="file" name="residential_proof" id="residential_proof" required><br><br>

        <label for="id_proof">Upload Legal ID Proof:</label>
        <input type="file" name="id_proof" id="id_proof" required><br><br>

        <label for="selfie">Upload Selfie:</label>
        <input type="file" name="selfie" id="selfie" required><br><br>

        <button type="submit">Submit KYC</button>
    </form>
</body>
</html>
