<?php
include '../includes/trois.php';
if(!in_array(verCookie(), $ADMIN_IDS) && basename($_SERVER['SCRIPT_NAME'])!="login.php"){header("Location: login.php");die();}
?>