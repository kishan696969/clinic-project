<?php
include "db.php";

// Default values
$patients = [];
$success = "";
$error = "";

// Get all patients
$pat_result = mysqli_query($conn, "SELECT ID, Name FROM patients ORDER BY Name ASC");
if ($pat_result) {
    while ($row = mysqli_fetch_assoc($pat_result)) {
        $patients[] = $row;
    }
}

// Form submit
if (isset($_POST['add_visit'])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $visit_date = mysqli_real_escape_string($conn, $_POST['visit_date']);
    $problem    = mysqli_real_escape_string($conn, $_POST['problem']);
    $medicine   = mysqli_real_escape_string($conn, $_POST['medicine']);

    if ($patient_id && $visit_date && $problem) {
        $sql = "INSERT INTO visits (patient_id, visit_date, problem, medicine)
                VALUES ('$patient_id', '$visit_date', '$problem', '$medicine')";
        if (mysqli_query($conn, $sql)) {
            $success = "Visit added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Patient, date and problem are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Visit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">

            <h2>Add Visit</h2>

            <?php if($success) echo "<p class='success'>$success</p>"; ?>
            <?php if($error) echo "<p class='error'>$error</p>"; ?>

            <form method="POST">

                <label>Patient</label>
                <select name="patient_id" required>
                    <option value="">Select Patient</option>
                    <?php foreach($patients as $p): ?>
                        <option value="<?= $p['ID']; ?>">
                            <?= $p['Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Visit Date</label>
                <input type="date" name="visit_date" required>

                <label>Problem / Notes</label>
                <textarea name="problem" required></textarea>

                <label>Medicine (Doctor Notes)</label>
                <textarea name="medicine" placeholder="Paracetamol 500mg – 1-0-1"></textarea>

                <button type="submit" name="add_visit">Save Visit</button>
            </form>

            <div class="nav-buttons">
                <a href="add_patient.php">⬅ Add Patient</a>
                <a href="search_patient.php">Next ➡ Search</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
