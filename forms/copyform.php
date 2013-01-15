<?php include_once '../inc/trois.php';
$f = new Form($_GET['id']);
if(!$f->id){errorMsg("No form found");}
$f->id=0;
$f->save();
header("Location: index.php");
die();
