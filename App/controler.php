<?php
	require_once '../vendor/autoload.php';
	$nlivro = new \App\NLivro();


	if(isset($_POST['func'])){
		if($_POST['func'] == "retirar")echo $nlivro->retirar($_POST['id']);
	}