<?php include '../inc/trois.php';
loginRequired();
accountRequired();
$account = new Account(verAccount());

$id = intval($_POST['account_id']);
$field = $_POST['field'];
$val = strval($_POST['val']);
//$account = new Account($id);
$account->modifyData($field,$val);
//var_dump($account->data);
die();