 <?php
// ================= SESSION DESTRUCTION =================
// Start the session to access session variables
session_start();
session_destroy(); 
header("Location: ../user/login.php");  
exit;
?>
