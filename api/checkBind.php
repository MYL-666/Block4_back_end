<?php
// ================= ROLE ID BINDING CHECK =================
// This script checks if a non-admin user has bound their role ID (student_id, parent_id, teacher_id)
// and redirects them to their profile page if the ID is not bound, unless they are already on it.

// Skip this check if the user is an admin
if($_SESSION['role']!='admin'){
    // Check if the role-specific ID (roleID) is not set or empty in the session
    if ((!isset($_SESSION['roleID']) || empty($_SESSION['roleID']))) {
        // Get the filename of the currently executing script
        $currentFile = basename($_SERVER['PHP_SELF']);
        
        // Define the specific profile pages where the user might need to bind their ID
        $profilePages = ['students.php', 'parents.php', 'teachers.php'];

        // Check if the user is NOT currently on one of the profile pages
        if (!in_array($currentFile, $profilePages)) {
            // Redirect the user to their specific profile page based on their role
            if ($_SESSION['role'] === 'student') {
                header("Location: students.php");
            } elseif ($_SESSION['role'] === 'parent') {
                header("Location: parents.php");
            } elseif ($_SESSION['role'] === 'teacher') {
                header("Location: teachers.php");
            } else {
                // If the role is unrecognized, log the user out as a safety measure
                header("Location: /api/logout.php"); 
            }
            exit; // Stop script execution after redirection
        } else {
            // User is on a profile page, but check if it's the *correct* one for their role
            if($_SESSION['role'] === 'parent' && $currentFile !== 'parents.php'){
                header("Location: parents.php");
                exit; // Stop script execution
            } elseif($_SESSION['role'] === 'student' && $currentFile !== 'students.php'){
                header("Location: students.php");
                exit; // Stop script execution
            } elseif($_SESSION['role'] === 'teacher' && $currentFile !== 'teachers.php'){
                header("Location: teachers.php");
                exit; // Stop script execution
            }
            // If they are on the correct profile page, no redirect is needed; they can proceed to bind their ID.
        }
    }
    // If roleID is set and not empty, the check passes, and the script requiring this file continues execution.
}
// If the user is an admin, the script does nothing and continues execution.
?>