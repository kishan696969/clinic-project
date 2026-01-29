<?php
include "db.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=clinic_report_".date('d-m-Y').".xls");
header("Pragma: no-cache"); 
header("Expires: 0");

// Query ma p.address fetch karyu
$sql = "SELECT v.visit_date, p.Name, p.mobile, p.address, v.problem, v.medicine 
        FROM visits v 
        JOIN patients p ON v.patient_id = p.ID 
        ORDER BY v.visit_date DESC";
$result = mysqli_query($conn, $sql);
?>

<table border="1">
    <tr style="background-color:#2c3e50; color:white; font-weight:bold; height:30px;">
        <th style="width:150px;">Date & Time</th>
        <th style="width:200px;">Patient Name</th>
        <th style="width:120px;">Mobile</th>
        <th style="width:200px;">Address</th> <th style="width:250px;">Problem</th>
        <th style="width:250px;">Medicine Given</th>
    </tr>

    <?php
    while($row = mysqli_fetch_assoc($result)) {
        $formatted_date = date("d/m/Y h:i A", strtotime($row['visit_date']));
        $addr = !empty($row['address']) ? $row['address'] : "N/A";
        
        echo "<tr>
                <td style='text-align:center;'>$formatted_date</td>
                <td style='font-weight:bold;'>{$row['Name']}</td>
                <td style='text-align:center;'>{$row['mobile']}</td>
                <td>$addr</td> <td>{$row['problem']}</td>
                <td>{$row['medicine']}</td>
              </tr>";
    }
    ?>
</table>