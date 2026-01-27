<?php
include "db.php";

$name    = $_POST['name'];
$age     = $_POST['age'];
$mobile  = $_POST['mobile'];
$address = $_POST['address'];

// ID Proofs
$aadhaar = !empty($_POST['aadhaar']) ? $_POST['aadhaar'] : NULL;
$ration  = !empty($_POST['ration']) ? $_POST['ration'] : NULL;
$voter   = !empty($_POST['voter_id']) ? $_POST['voter_id'] : NULL;
$pan     = !empty($_POST['pan_card']) ? $_POST['pan_card'] : NULL;

// --- PHOTO SAVING LOGIC ---
$photo_path = NULL;

if(!empty($_POST['webcam_image'])) {
    $data = $_POST['webcam_image'];
    
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $filename = time() . "_cam.jpg";
    $target_file = $target_dir . $filename;

    if(file_put_contents($target_file, $data)){
        $photo_path = $target_file;
    }
}

$sql = "INSERT INTO patients (name, age, mobile, address, aadhaar_no, ration_no, voter_id, pan_card, photo_path)
        VALUES ('$name', '$age', '$mobile', '$address', 
        " . ($aadhaar ? "'$aadhaar'" : "NULL") . ", 
        " . ($ration ? "'$ration'" : "NULL") . ", 
        " . ($voter ? "'$voter'" : "NULL") . ", 
        " . ($pan ? "'$pan'" : "NULL") . ", 
        " . ($photo_path ? "'$photo_path'" : "NULL") . ")";

if(mysqli_query($conn, $sql)){
    echo "<script>alert('Patient Registered Successfully!'); window.location='add_patient.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>