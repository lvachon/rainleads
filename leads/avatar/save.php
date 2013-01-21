<?php ob_start();
include_once '../inc/trois.php';
loginRequired();
if($_FILES['photo']['tmp_name']){
	$con = conDB();
	exec("convert {$_FILES['photo']['tmp_name']} -thumbnail '256x256>' " . getcwd() . "/tmp.jpg");
	if(file_exists(getcwd() . "/tmp.jpg")){
		$user_id = intval(verCookie());
		exec("mv " . getcwd() . "/tmp.jpg " . getcwd() . "/".$user_id.".jpg");
		echo "<script type='text/javascript' src='js/jquery.js'></script><script>parent.document.location.href='".$HOME_URL."account';</script>";
	}else{	
		
		errorMsg("A problem occurred saving your profile picture",true);
	}
}else{
	errorMsg("No file was found!",true);
}
