<?php
session_start();
session_destroy(); // Destroy the session
header("Location: admin_login.php");
exit;
?>
