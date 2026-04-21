<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = basename($_REQUEST[ 'id' ]);

	if (!preg_match('/^[a-zA-Z0-9_-]+$/', $id)) {
        die("Security Alert: Invalid ID format.");
    }


	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			$query  = "SELECT first_name, last_name FROM users WHERE user_id = ?;";
			$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

			// 2. Colleghiamo la variabile $id (assumendo sia un intero "i" o stringa "s")
			// Nota: anche se $id è tra apici nel codice originale, qui NON servono.
			mysqli_stmt_bind_param($stmt, "i", $id);

			try {
				// 3. Eseguiamo lo statement
				mysqli_stmt_execute($stmt);
				// 4. Otteniamo i risultati
				$result = mysqli_stmt_get_result($stmt);
			} catch (Exception $e) {
				// Gestione silenziosa degli errori per evitare info leakage
				error_log($e->getMessage());
			}
		case SQLITE:
			global $sqlite_db_connection;

			#$sqlite_db_connection = new SQLite3($_DVWA['SQLITE_DB']);
			#$sqlite_db_connection->enableExceptions(true);

			$query  = "SELECT first_name, last_name FROM users WHERE user_id = :id;";
			$stmt = $sqlite_db_connection->prepare($query);
			$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
			#print $query;
			try {
				$results = $stmt->execute();
			} catch (Exception $e) {
				echo 'Caught exception: ' . $e->getMessage();
				exit();
			}

			if ($results) {
				while ($row = $results->fetchArray()) {
					// Get values
					$first = $row["first_name"];
					$last  = $row["last_name"];

					// Feedback for end user
					$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
				}
			} else {
				echo "Error in fetch ".$sqlite_db->lastErrorMsg();
			}
			break;
	} 
}

?>
