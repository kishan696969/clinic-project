<?php
include "db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daily Patient Report</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content">

        <div class="container">

            <h2>Daily Patient Report</h2>

            <table>
                <tr>
                    <th>Date</th>
                    <th>Total Patients</th>
                </tr>

                <?php
                $sql = "SELECT DATE(visit_date) AS visit_day,
                               COUNT(*) AS total
                        FROM visits
                        GROUP BY DATE(visit_date)
                        ORDER BY visit_day DESC";

                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>
                                <td>{$row['visit_day']}</td>
                                <td>{$row['total']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='2' class='error'>No data found</td>
                          </tr>";
                }
                ?>
            </table>

            <div class="nav-buttons">
                <a href="search_patient.php">⬅ Search Patient</a>
                <a href="add_patient.php">➕ Add Patient</a>
            </div>

        </div>

    </div>
</div>

</body>
</html>
