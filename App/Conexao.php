<?php

	$servername = "192.168.100.20";
	$username = "ifrn";
	$password = "ifrn";
	$db_name = "sorteio";

	$connect = mysqli_connect($servername, $username, $password, $db_name);

	if(mysqli_connect_error()){
		echo "Falha na conexão: ".mysqli_connect_error();
	}