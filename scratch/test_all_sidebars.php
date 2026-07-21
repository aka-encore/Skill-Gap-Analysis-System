<?php
session_start();

// Test Student Sidebar
$_SESSION['user_id'] = 7;
$_SESSION['user_role'] = 'student';
$_SESSION['profile_id'] = 1;
$_SESSION['user_name'] = 'Arjun Kapoor';

ob_start();
include __DIR__ . '/../includes/sidebar.php';
$studentSidebar = ob_get_clean();

// Test Faculty Sidebar
$_SESSION['user_role'] = 'faculty';
ob_start();
include __DIR__ . '/../includes/sidebar.php';
$facultySidebar = ob_get_clean();

// Test Admin Sidebar
$_SESSION['user_role'] = 'admin';
ob_start();
include __DIR__ . '/../includes/sidebar.php';
$adminSidebar = ob_get_clean();

echo "Student Sidebar Length: " . strlen($studentSidebar) . " bytes\n";
echo "Faculty Sidebar Length: " . strlen($facultySidebar) . " bytes\n";
echo "Admin Sidebar Length: " . strlen($adminSidebar) . " bytes\n";

if (strpos($studentSidebar, 'title="Dashboard"') !== false &&
    strpos($facultySidebar, 'title="Manage Assessments"') !== false &&
    strpos($adminSidebar, 'title="Admin Dashboard"') !== false) {
    echo "SUCCESS: All 3 sidebars (Student, Faculty, Admin) render cleanly with tooltips!\n";
} else {
    echo "WARNING: Sidebar signature mismatch.\n";
}
