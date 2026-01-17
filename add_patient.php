<html>
<head>
    <title>Add Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<body>

<div class="layout">

    <?php include "navbar.php"; ?>

    <div class="content">

        <div class="container">

            <h2>Add Patient</h2>

            <form method="POST" action="save_patient.php">

                <label>Name</label>
                <input type="text" name="name" required>

                <label>Age</label>
                <input type="number" name="age" required>

                <label>Mobile</label>
                <input type="text" name="mobile" required>

                <label>Address</label>
                <input type="text" name="address">

                <label>Aadhaar No (Optional)</label>
                <input type="text" name="aadhaar">

                <label>Ration Card No (Optional)</label>
                <input type="text" name="ration">

                <button type="submit">Save Patient</button>
            </form>

            <div class="nav-buttons">
                <span></span>
                <a href="add_visit.php">Next → Add Visit</a>
            </div>

        </div>

    </div>

</div>

</body>


</body>
</html>
