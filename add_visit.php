<?php
include "db.php";

$success = "";
$error = "";

$pat_result = mysqli_query($conn, "SELECT ID, Name, mobile, age, address, aadhaar_no FROM patients ORDER BY Name ASC");

if (isset($_POST['add_visit'])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    
    $raw_date = $_POST['visit_date'];
    
    $current_time = date("H:i:s"); 
    $visit_date = $raw_date . " " . $current_time;

    $problem    = mysqli_real_escape_string($conn, $_POST['problem']);
    $medicine   = mysqli_real_escape_string($conn, $_POST['medicine']);

    if ($patient_id && $visit_date && $problem) {
        $sql = "INSERT INTO visits (patient_id, visit_date, problem, medicine)
                VALUES ('$patient_id', '$visit_date', '$problem', '$medicine')";
        if (mysqli_query($conn, $sql)) {
            $success = "✅ Visit Added Successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Please select a patient.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Visit</title>
    
    <link rel="stylesheet" href="style.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container .select2-selection--single {
            height: 50px !important;
            padding: 10px;
            border: 1px solid #ddd !important;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
            right: 10px !important;
        }
        
        /* Dropdown Item Design */
        .p-option { padding: 5px; border-bottom: 1px solid #f0f0f0; }
        .p-top { display: flex; justify-content: space-between; font-size: 16px; color: #2c3e50; font-weight: 600; }
        .p-mobile { color: #2980b9; }
        .p-bottom { font-size: 13px; color: #7f8c8d; margin-top: 2px; }
        .p-proof { font-size: 11px; background: #eee; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block; }
    </style>
</head>
<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">

            <h2>👨‍⚕️ Add New Visit</h2>

            <?php if($success) echo "<div style='background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;'>$success</div>"; ?>
            <?php if($error) echo "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>$error</div>"; ?>

            <form method="POST">

                <label>🔍 Select Patient (Search Name / Mobile)</label>
                
                <select name="patient_id" id="patient_select" required style="width: 100%;">
                    <option value="">Search Patient...</option>
                    <?php 
                    while($p = mysqli_fetch_assoc($pat_result)): 
                        $proof = "";
                        if(!empty($p['aadhaar_no'])) { $proof .= "Aadhar: " . $p['aadhaar_no']; }
                    ?>
                        <option value="<?= $p['ID']; ?>" 
                                data-name="<?= $p['Name']; ?>"
                                data-mobile="<?= $p['mobile']; ?>"
                                data-age="<?= $p['age']; ?>"
                                data-address="<?= $p['address']; ?>"
                                data-proof="<?= $proof; ?>">
                            <?= $p['Name']; ?> - <?= $p['mobile']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <div style="margin-top: 15px;"></div>

                <label>📅 Visit Date</label>
                <input type="date" name="visit_date" value="<?= date('Y-m-d'); ?>" required>

                <label>🤒 Problem / Notes</label>
                <textarea name="problem" required placeholder="Fever, Cold, Body Pain..."></textarea>

                <label>💊 Medicine (Doctor Notes)</label>
                <textarea name="medicine" placeholder="Paracetamol 500mg – 1-0-1"></textarea>

                <button type="submit" name="add_visit">✅ Save Visit</button>
            </form>

            <div class="nav-buttons">
                <a href="add_patient.php">⬅ Add Patient</a>
                <a href="search_patient.php">Search History ➡</a>
            </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        
        // Custom Design Function
        function formatPatient (patient) {
            if (!patient.id) { return patient.text; }
            
            var name = $(patient.element).data('name');
            var mobile = $(patient.element).data('mobile');
            var age = $(patient.element).data('age');
            var address = $(patient.element).data('address');
            var proof = $(patient.element).data('proof');

            var $design = $(
                '<div class="p-option">' +
                    '<div class="p-top">' +
                        '<span>👤 ' + name + '</span>' +
                        '<span class="p-mobile">📱 ' + mobile + '</span>' +
                    '</div>' +
                    '<div class="p-bottom">' +
                        '<span>🎂 ' + age + ' Years</span> | ' +
                        '<span>🏠 ' + address + '</span>' +
                    '</div>' +
                    (proof ? '<div class="p-proof">🆔 ' + proof + '</div>' : '') +
                '</div>'
            );
            return $design;
        }

        function formatPatientSelection (patient) {
            if (!patient.id) { return patient.text; }
            var name = $(patient.element).data('name');
            var mobile = $(patient.element).data('mobile');
            return name + " (" + mobile + ")";
        }

        // Initialize Select2
        $('#patient_select').select2({
            placeholder: "Type Name or Mobile to Search...",
            allowClear: true,
            templateResult: formatPatient,
            templateSelection: formatPatientSelection
        });
    });
</script>

</body>
</html>