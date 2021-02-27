<?php
//Sind die Cookies gesetzt?
if(isset($_COOKIE['authWebToken']) && isset($_COOKIE['username'])) {
	//lade die Cookie-Daten
	$username_cookie = $_COOKIE["username"]; 
	$authCode_cookie = $_COOKIE["authWebToken"]; 
		
	//Verbindung mit DB herstellen
	include '../../home/connect_mysql.php'; 
		
	//Suche nach dem gleichen AuthCode
	$sql = "SELECT * FROM authcode_table WHERE Token='$authCode_cookie'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$found_token_owner = $row["User"];
			}
	} else {
		//AuthCode nicht gefunden
		//Reset der Cookies und redirect zur Homepage
		setcookie("username", "", time() - 13600,'/');
		setcookie("authWebToken", "", time() - 13600,'/');
		header("Refresh:0; url=/");
		die("We couldn't find your session in our database. Redirecting to our homepage...");
	}
	//Prüfung ober der in der DB für den AuthCode Token hinterlegte Username mit Username Cookie übereinstimmt
	//Sicherheitsprüfung für unbrechtigten Zugang
	if ($found_token_owner != $username_cookie) {
		//Reset der Cookies und redirect zur Homepage
		setcookie("username", "", time() - 13600,'/');
		setcookie("authWebToken", "", time() - 13600,'/');
		header("Refresh:0; url=/");
		die("wrong owner detected");
	}
	//NWV
	include '../../home/connect_auth_mysql.php'; 
	$sql = "SELECT * FROM user_data WHERE username='$username_cookie'"; 
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while ($row = $result->fetch_assoc()) {
			$user_email_address = $row["email_address"];
		}
	} else {
		echo "0 results";
	}
	include '../../home/connect_mysql.php'; 
	//Lade Benutzerdaten aus der DB
	$sql = "SELECT * FROM user_data WHERE username='$username_cookie'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$userID = $row["userID"];
			$user_rank = $row["rank"];
			$user_iban = $row["iban"];
			$user_avatar_url = $row["profile_pic_url"];
			$user_company_id = $row["userCompanyID"];
			$user_team_role = $row["staff_role"];
			$user_patreon_state = $row["patreon_state"];
			$bank_balance_user = $row["bank_balance"];
		}
	} else {
		//Der Benutzer konnte in der DB nicht gefunden werden
		die("We're sorry but we couldn't find your profile");
	}
	//Ist der Benutzer in einer Firma?
	if($user_company_id != "0"){
		//Dann lade die Berechtigungen des Benutzers in der Firma
		$sql = "SELECT * FROM rank WHERE name='$user_rank' AND forCompanyID=$user_company_id";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$SeeBank = $row["SeeBank"];
				$EditProfile = $row["EditProfile"];
				$SeeLogbook = $row["SeeLogbook"];
				$EditLogbook = $row["EditLogbook"];
				$UseBank = $row["UseBank"];
				$EditEmployees = $row["EditEmployees"];
				$EditSalary = $row["EditSalary"];
			}
		}
		//Lade den Namen der Firma
		$sql = "SELECT * FROM company_information_table WHERE id=$user_company_id";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$user_company_name = $row["name"];
			}
		}
	}
	//aktualisiere zuletzt online
	$sql = "UPDATE user_data SET `last_seen`=NOW()  WHERE username='$username_cookie'";
	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error updating record: " . $conn->error;
		die();
	}
}else{
	//Keine Cookies gefunden
	//Der Benutzer ist nicht eingeloggt
	//Redirect zur Homepage
	header("Refresh:0; url=/");
	die("Sorry, but you're not logged in. Redirecting to homepage...");
}
		?>
