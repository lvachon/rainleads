<?php include_once '../inc/trois.php';
if(!intval($_POST['form_id'])){
	die("No form");
}
if(!intval(verCookie())){
	die("Login expired.");
}
$account = new Account(verAccount());
$f = new Form($_POST['form_id']);
if($f->account_id!=$account->id){errorMsg("This form is not yours to edit");die();}
$f->data['last_editor']=strval($viewer->id);
if(!$f->id){die("error: Invalid form id");}
if($_POST['action']=="reorder"){
	$ids = explode(",",$_POST['ids']);
	$nf = new Form();
	$nf->data = $f->data;
	$nf->id = $f->id;
	foreach($ids as $id){
		$nf->addElem($f->getElem($id));
	}
	$nf->save();
	$f = new Form($_POST['form_id']);
	die ($f->getEditHTML());
}

if($_POST['action']=="remove"){
	$f->delElem($_POST['elem_id']);
	$f->save();
	$f = new Form($_POST['form_id']);
	die ($f->getEditHTML());
}
if($_POST['action']=="append"){
	$x = json2array($_POST['elem_data']);
	if(strpos(strtolower($x['name']),"card")!==false){
		htmlEmail("admin@rainleads.com","Potential CC field","The form with id# {$f->id} has a form element which may be collecting cc info");
	}
	if(strpos(strtolower($x['label']),"card")!==false){
		htmlEmail("admin@rainleads.com","Potential CC field","The form with id# {$f->id} has a form element which may be collecting cc info");
	}
	$f->addElem(new FormElement($x));
	$f->save();
	$f = new Form($_POST['form_id']);
	die ($f->getEditHTML());
}

if($_POST['action']=="edit_elem"){
	$x = json2array($_POST['elem_data']);
	if(strpos(strtolower($x['name']),"card")!==false){
		htmlEmail("admin@rainleads.com","Potential CC field","The form with id# {$f->id} has a form element which may be collecting cc info");
	}
	if(strpos(strtolower($x['label']),"card")!==false){
		htmlEmail("admin@rainleads.com","Potential CC field","The form with id# {$f->id} has a form element which may be collecting cc info");
	}
	$oe = $f->getElem($_POST['elem_id']);
	$ne =  new FormElement(json2array($_POST['elem_data']));
	$ne->data['name']=$oe->data['name'];
	$f->setElem($_POST['elem_id'],$ne);
	$f->save();
	die ("ok");
}

if($_POST['action']=="edit_styles"){
	$f->data['styles']=$_POST['styles'];
	$f->data['width']=strval(intval($_POST['width']));
	$f->save();
	die("ok");
}
function json2array($x){
	$a = array();
	$j = json_decode($x);
	foreach($j as $k=>$v){
		$a[$k]=$v;
	}
	return $a;
}