<?php  include '../inc/trois.php';
$id = intval($_GET['id']);
$viewer = new User($id);
$viewer->login();
header("Location: index.php");die();?>