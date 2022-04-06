<?php 

	// connect to the database
	$conn = mysqli_connect('127.0.0.1', 'baljit', 'Baljit2020.', 'it490');

	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}

?>