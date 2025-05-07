<?php
	// Datenbankinfo aus Globals abrufen --> zu finden in functions.php
	global $projDB_servername;
	global $projDB_username;
	global $projDB_password;
	global $projDB_dbname;
	global $projDB_tablename;
	
	// Query mit allen abzufragenden Feldern
	$pro_query = 'SELECT Projektkürzel, Projektname, `Jahr Fertigstellung` FROM `' . $projDB_tablename . '` ORDER BY `Jahr Fertigstellung` DESC';
	
	// Verbindung zur Datenbank aufbauen
	try {
		$conn = new PDO("mysql:host=$projDB_servername;dbname=$projDB_dbname", $projDB_username, $projDB_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} 
	catch(PDOException $e) {
		die("Verbindung zur Projektdatenbank fehlgeschlagen: " . $e->getMessage());
	}
	
	// Query ausführen und Daten extrahieren
	try {
		$result = $conn->query($pro_query)->fetchAll();
	}
	catch(PDOException $e) {
		die("pro_query fehlgeschlagen: " . $e->getMessage());
	}
	unset($pro_query);

?>