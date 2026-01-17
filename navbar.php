<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h3>🏥 CLINIC OS</h3>
    
    <a href="add_patient.php" class="<?php echo ($current_page == 'add_patient.php') ? 'active' : ''; ?>">
        ➕ Add Patient
    </a>
    
    <a href="add_visit.php" class="<?php echo ($current_page == 'add_visit.php') ? 'active' : ''; ?>">
        📝 Add Visit
    </a>
    
    <a href="search_patient.php" class="<?php echo ($current_page == 'search_patient.php') ? 'active' : ''; ?>">
        🔍 Search Patient
    </a>
    
    <a href="daily_report.php" class="<?php echo ($current_page == 'daily_report.php') ? 'active' : ''; ?>">
        📊 Daily Report
    </a>
</div>


