<?php
include "db.php";

$name    = isset($_GET['name']) ? $_GET['name'] : '';
$mobile  = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$aadhaar = isset($_GET['aadhaar']) ? $_GET['aadhaar'] : '';
$voter   = isset($_GET['voter']) ? $_GET['voter'] : '';
$pan     = isset($_GET['pan']) ? $_GET['pan'] : '';
$ration  = isset($_GET['ration']) ? $_GET['ration'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Patient</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
        }
        .optional-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px dashed #bdc3c7;
            margin-top: 10px;
        }
        .optional-title {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="layout">
    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">
            <h2>🔍 Search Patient History</h2>

            <form method="GET" action="">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Patient Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="Enter Mobile">
                    </div>
                </div>

                <div class="optional-section">
                    <div class="optional-title">🔽 ID Proofs (Optional - Search by any one)</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Aadhaar No</label>
                            <input type="text" name="aadhaar" value="<?php echo htmlspecialchars($aadhaar); ?>">
                        </div>
                        <div class="form-group">
                            <label>Voter ID</label>
                            <input type="text" name="voter" value="<?php echo htmlspecialchars($voter); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>PAN Card</label>
                            <input type="text" name="pan" value="<?php echo htmlspecialchars($pan); ?>">
                        </div>
                        <div class="form-group">
                            <label>Ration Card</label>
                            <input type="text" name="ration" value="<?php echo htmlspecialchars($ration); ?>">
                        </div>
                    </div>
                </div>

                <button type="submit">🔍 Find Patient</button>
            </form>

            <?php
            if($name || $mobile || $aadhaar || $voter || $pan || $ration){

                $sql = "SELECT * FROM patients WHERE 1=1";

                if($name)    $sql .= " AND Name LIKE '%$name%'";
                if($mobile)  $sql .= " AND mobile LIKE '%$mobile%'";
                if($aadhaar) $sql .= " AND aadhaar_no LIKE '%$aadhaar%'";
                if($voter)   $sql .= " AND voter_id LIKE '%$voter%'";
                if($pan)     $sql .= " AND pan_card LIKE '%$pan%'";
                if($ration)  $sql .= " AND ration_no LIKE '%$ration%'";

                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $pid = $row['ID'];
                        $photo = $row['photo_path'] ? $row['photo_path'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                        
                        echo "<div style='border:1px solid #ddd; padding:15px; margin-top:20px; border-radius:8px; background:#fff;'>";
                        
                        echo "<div style='display:flex; gap:20px; align-items:start;'>
                                <img src='$photo' style='width:100px; height:100px; border-radius:8px; object-fit:cover; border:1px solid #eee;'>
                                <div style='flex:1;'>
                                    <h3 style='margin:0 0 10px 0; color:#2c3e50; border-bottom:1px solid #eee; padding-bottom:5px;'>
                                        {$row['Name']} 
                                    </h3>
                                    <div style='display:grid; grid-template-columns: 1fr 1fr; gap:10px; font-size:14px; color:#555;'>
                                        <span>📞 <b>Mobile:</b> {$row['mobile']}</span>
                                        <span>🎂 <b>Age:</b> {$row['age']}</span>
                                        <span>🏠 <b>Address:</b> {$row['address']}</span>
                                        <span>🆔 <b>Aadhaar:</b> ".($row['aadhaar_no']?:'-')."</span>
                                        <span>🗳️ <b>Voter ID:</b> ".($row['voter_id']?:'-')."</span>
                                        <span>💳 <b>PAN:</b> ".($row['pan_card']?:'-')."</span>
                                    </div>
                                </div>
                              </div>";

                        // Visits Table
                        $v_sql = "SELECT * FROM visits WHERE patient_id = $pid ORDER BY visit_date DESC";
                        $v_res = mysqli_query($conn, $v_sql);

                        if(mysqli_num_rows($v_res) > 0){
                            echo "<table style='margin-top:15px;'>
                                    <tr style='background:#f1f2f6;'>
                                        <th>Date</th>
                                        <th>Problem</th>
                                        <th>Medicine</th>
                                    </tr>";
                            while($v = mysqli_fetch_assoc($v_res)){
                                $d = date("d/m/Y", strtotime($v['visit_date'])); 
                                echo "<tr>
                                        <td><b>$d</b></td>
                                        <td>{$v['problem']}</td>
                                        <td>{$v['medicine']}</td>
                                      </tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p style='color:#e74c3c; margin-top:10px;'>No previous visits found.</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p class='error' style='text-align:center; margin-top:20px;'>No patient found with these details.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>