<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports & Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">
    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; border-bottom: 2px solid #f0f2f5; padding-bottom: 10px;">
                <h2 style="border:none; margin:0;">📊 Daily Report</h2>
                
                <form action="export_report.php" method="post" target="_blank">
                    <button type="submit" style="background:#27ae60; width:auto; padding: 10px 20px;">
                        📥 Download Excel
                    </button>
                </form>
            </div>

            <?php
            $today = date('Y-m-d');
            $q_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM visits WHERE DATE(visit_date) = '$today'"));
            ?>

            <div class="dashboard-panel">
                <div class="dash-item">
                    <h2><?= $q_today['c'] ?></h2>
                    <p>Today's Patients</p>
                </div>
                </div>

            <h3 style="margin-top:30px; color:#34495e;">📋 Patient Log (Last 50 Visits)</h3>
            
            <table>
                <tr>
                    <th>Date</th>
                    <th>Patient Name</th>
                    <th>Mobile</th>
                    <th>Problem</th>
                    <th>Medicine</th>
                </tr>

                <?php
                // List last 50 visits
                $sql = "SELECT v.visit_date, v.problem, v.medicine, p.Name, p.mobile 
                        FROM visits v 
                        JOIN patients p ON v.patient_id = p.ID 
                        ORDER BY v.visit_date DESC LIMIT 50";
                
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $d = date("d/m/Y h:i A", strtotime($row['visit_date']));
                        echo "<tr>
                                <td>$d</td>
                                <td style='font-weight:bold;'>{$row['Name']}</td>
                                <td>{$row['mobile']}</td>
                                <td>{$row['problem']}</td>
                                <td>{$row['medicine']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No visits found yet.</td></tr>";
                }
                ?>
            </table>

        </div>
    </div>
</div>
</body>
</html>