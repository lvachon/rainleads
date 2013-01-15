<?php include '../inc/trois.php';
$id = intval($_GET['id']);
if(!$id){die("No form");}
$form = new Form($id);
echo $form->getHTML(true);
?>
