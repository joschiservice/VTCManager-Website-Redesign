<?php
//Verbindung mit DB herstellen
	$host = 'localhost:3306';    
	$conn = mysqli_connect($host, "joschua", "","vtcmanager_old"); 
	if(! $conn ){  
		die("Verbindung zur Datenbank (".$host." ist fehlgeschlagen.");  
	} 
?>
