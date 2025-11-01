<?php
session_start();
require 'function.php';
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>

