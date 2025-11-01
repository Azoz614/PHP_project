<?php
session_start();
require_once __DIR__ . '/admin/function.php'; 
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>


