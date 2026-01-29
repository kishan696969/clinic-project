<?php
include "db.php";

$success = "";
$error = "";

$sql = "SELECT ID, Name, mobile, age, address, aadhaar_no, ration_no, voter_id, pan_card FROM patients ORDER BY Name ASC";
$pat_result = mysqli_query($conn, $sql);

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
        .id-badge {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-right: 5px;
            display: inline-block;
            margin-top: 4px;
            border: 1px solid #ddd;
        }
        .bg-aadhar { background: #fff3cd; color: #856404; border-color: #ffeeba; } 
        .bg-pan    { background: #d4edda; color: #155724; border-color: #c3e6cb; } 
        .bg-voter  { background: #f8d7da; color: #721c24; border-color: #f5c6cb; } 
        .bg-ration { background: #e2e3e5; color: #383d41; border-color: #d6d8db; } 
    </style>
</head>
<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">

            <h2>👨‍⚕️ Add New Visit</h2>

            <?php if($success) echo "<div style='background:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border-radius:5px;'>$success</div>"; ?>
            <?php if($error) echo "<div style='background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-radius:5px;'>$error</div>"; ?>

            <form method="POST">

                <label>🔍 Select Patient (Search by Name, Mobile, Aadhar, PAN, Voter ID...)</label>
                
                <select name="patient_id" id="patient_select" required style="width: 100%;">
                    <option value="">Type to search...</option>
                    <?php 
                    while($p = mysqli_fetch_assoc($pat_result)): 
                        $mob = !empty($p['mobile']) ? $p['mobile'] : "No Mobile";
                    ?>
                        <option value="<?= $p['ID']; ?>" 
                                data-name="<?= $p['Name']; ?>"
                                data-mobile="<?= $mob; ?>"
                                data-age="<?= $p['age']; ?>"
                                data-address="<?= $p['address']; ?>"
                                data-aadhar="<?= $p['aadhaar_no']; ?>"
                                data-ration="<?= $p['ration_no']; ?>"
                                data-voter="<?= $p['voter_id']; ?>"
                                data-pan="<?= $p['pan_card']; ?>">
                            
                            <?= $p['Name']; ?> | <?= $mob; ?> | 
                            <?= $p['aadhaar_no']; ?> | <?= $p['ration_no']; ?> | 
                            <?= $p['voter_id']; ?> | <?= $p['pan_card']; ?>
                        
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
            
            var el = $(patient.element);
            var name = el.data('name');
            var mobile = el.data('mobile');
            var age = el.data('age');
            var address = el.data('address');

            // ID Proofs Check logic
            var badges = "";
            if(el.data('aadhar')) badges += '<span class="id-badge bg-aadhar">Aadhar: '+el.data('aadhar')+'</span> ';
            if(el.data('pan'))    badges += '<span class="id-badge bg-pan">PAN: '+el.data('pan')+'</span> ';
            if(el.data('voter'))  badges += '<span class="id-badge bg-voter">Voter: '+el.data('voter')+'</span> ';
            if(el.data('ration')) badges += '<span class="id-badge bg-ration">Ration: '+el.data('ration')+'</span> ';

            var $design = $(
                '<div class="p-option">' +
                    '<div class="p-top">' +
                        '<span> Name : ' + name + '</span>  |  ' +
                        '<span class="p-mobile"> 📞 : ' + mobile + '</span>' +
                    '</div>' +
                    '<div class="p-bottom">' +
                        '<span> Age : ' + age + '</span>  |  ' +
                        '<span> Address : ' + (address ? address : 'Unknown') + '</span>' +
                    '</div>' +
                    (badges ? '<div>' + badges + '</div>' : '') + 
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

        $('#patient_select').select2({
            placeholder: "Search by Name, Mobile, Aadhar, PAN, Voter ID...",
            allowClear: true,
            templateResult: formatPatient,
            templateSelection: formatPatientSelection
        });
    });
</script>

</body>
</html>