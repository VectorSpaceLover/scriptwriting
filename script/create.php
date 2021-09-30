<?php

include('func.php');

$db = new DbConnection();
if(isset($_POST['title']) && isset($_POST['lines']) && $_POST['title'] && $_POST['lines']){
	$db->create($_POST['title'], $_POST['lines']);
}else
{
	print_r($_POST);
	echo 'Data error';
}