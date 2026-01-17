<?php
include "db.php";

// Safe GET variables
$name    = isset($_GET['name']) ? $_GET['name'] : '';
$mobile  = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$aadhaar = isset($_GET['aadhaar']) ? $_GET['aadhaar'] : '';
$ration  = isset($_GET['ration']) ? $_GET['ration'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content">

        <div class="container">

            <h2>Search Patient</h2>

            <form method="GET" action="">

                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">

                <label>Mobile</label>
                <input type="text" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">

                <label>Aadhaar No</label>
                <input type="text" name="aadhaar" value="<?php echo htmlspecialchars($aadhaar); ?>">

                <label>Ration Card No</label>
                <input type="text" name="ration" value="<?php echo htmlspecialchars($ration); ?>">

                <button type="submit">Search Patient</button>
            </form>

            <?php
            if($name || $mobile || $aadhaar || $ration){

                $sql = "SELECT * FROM patients WHERE 1=1";
                if($name)    $sql .= " AND Name LIKE '%$name%'";
                if($mobile)  $sql .= " AND mobile LIKE '%$mobile%'";
                if($aadhaar) $sql .= " AND aadhaar_no LIKE '%$aadhaar%'";
                if($ration)  $sql .= " AND ration_no LIKE '%$ration%'";

                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){

                        $patient_id = $row['ID'];
                        echo "<hr style='margin:25px 0;'>";

                        echo "<h3 style='color:#1f3c88'>
                                {$row['Name']} | {$row['mobile']}
                              </h3>";

                        echo "<p>
                                Aadhaar: ".($row['aadhaar_no'] ?: 'N/A')." |
                                Ration: ".($row['ration_no'] ?: 'N/A')."
                              </p>";

                        $visit_sql = "SELECT * FROM visits 
                                      WHERE patient_id = $patient_id 
                                      ORDER BY visit_date DESC";
                        $visit_result = mysqli_query($conn, $visit_sql);

                        if(mysqli_num_rows($visit_result) > 0){
                            echo "<table>
                                    <tr>
                                        <th>Date</th>
                                        <th>Problem</th>
                                        <th>Medicine</th>
                                    </tr>";
                            while($v = mysqli_fetch_assoc($visit_result)){
                                echo "<tr>
                                        <td>{$v['visit_date']}</td>
                                        <td>{$v['problem']}</td>
                                        <td>{$v['medicine']}</td>
                                      </tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p class='error'>No visits found.</p>";
                        }
                    }
                } else {
                    echo "<p class='error'>No patient found.</p>";
                }
            }
            ?>

            <div class="nav-buttons">
                <a href="add_visit.php">⬅ Add Visit</a>
                <a href="daily_report.php">Next ➜ Report</a>
            </div>

        </div>

    </div>
</div>

</body>
</html>
