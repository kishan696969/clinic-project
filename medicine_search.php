<?php
include "db.php";

if(isset($_GET['q'])){
    $q = mysqli_real_escape_string($conn, $_GET['q']);

    $res = mysqli_query($conn,
        "SELECT medicine_name FROM medicines
         WHERE medicine_name LIKE '%$q%'
         LIMIT 10");

    while($row = mysqli_fetch_assoc($res)){
        echo "<div class='med-item'>".$row['medicine_name']."</div>";
    }
}
?>
