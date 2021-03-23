<?php

// nome di host
$dbhost = "localhost";
// username dell'utente in connessione
$dbuser = "root";
// password dell'utente
$dbpassword = "";
// nome del database
$dbname = "test";

// stringa di connessione al DBMS
//$conn = new mysqli($host, $user, $password, $db);
$conn=mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

// verifica su eventuali errori di connessione
if ($conn->connect_errno) {
	echo "Connessione fallita: ". $conn->connect_error . ".";
	exit();
}
?>