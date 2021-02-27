<?php
//Verbindung mit DB herstellen
	$host = 'localhost:3306';    
	$conn = mysqli_connect($host, "joschua", "","nwv_api_old"); 
	if(! $conn ){  
		die("Verbindung zur Auth Datenbank (".$host." ist fehlgeschlagen.");  
	} 
?>