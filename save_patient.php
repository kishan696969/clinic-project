<?php
include "db.php";  // Database connection include

$name    = $_POST['name'];
$age     = $_POST['age'];
$mobile  = $_POST['mobile'];
$address = $_POST['address'];
$aadhaar = !empty($_POST['aadhaar']) ? $_POST['aadhaar'] : NULL;
$ration  = !empty($_POST['ration']) ? $_POST['ration'] : NULL;

$sql = "INSERT INTO patients (name, age, mobile, address, aadhaar_no, ration_no)
        VALUES ('$name', '$age', '$mobile', '$address', " .
        ($aadhaar ? "'$aadhaar'" : "NULL") . ", " .
        ($ration ? "'$ration'" : "NULL") . ")";

if(mysqli_query($conn, $sql)){
    echo "Patient Added Successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
