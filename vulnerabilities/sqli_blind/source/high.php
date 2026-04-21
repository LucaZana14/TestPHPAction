<?php

if( isset( $_COOKIE[ 'id' ] ) ) {
	// Get input
	$id = basename($_COOKIE[ 'id' ]);
	$exists = false;

	if (!preg_match('/^[a-zA-Z0-9_-]+$/', $id)) {
        die("Security Alert: Invalid ID format.");
    }

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			// 1. Prepariamo la query con il segnaposto '?'
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1;";
            
            // 2. Prepariamo lo statement
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

            $exists = false;

            if ($stmt) {
                // 3. Colleghiamo il parametro (bind). 's' sta per string.
                // Questo disarma qualsiasi tentativo di SQL Injection.
                mysqli_stmt_bind_param($stmt, "s", $id);

                try {
                    // 4. Eseguiamo lo statement
                    mysqli_stmt_execute($stmt);
                    
                    // 5. Otteniamo il set di risultati
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if ($result) {
                        $exists = (mysqli_num_rows($result) > 0);
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $exists = false;
                }
                
                // Chiudiamo lo statement
                mysqli_stmt_close($stmt);
            }

            // Chiudiamo la connessione (mantenendo la logica originale di DVWA)
            ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
            break;
		case SQLITE:
			global $sqlite_db_connection;

			$query  = "SELECT first_name, last_name FROM users WHERE user_id = :id LIMIT 1;";
			$stmt = $sqlite_db_connection->prepare($query);
			$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
			try {
				$results = $stmt->execute();
				$row = $results->fetchArray();
				$exists = $row !== false;
			} catch(Exception $e) {
				$exists = false;
			}

			break;
	}

	if ($exists) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	}
	else {
		// Might sleep a random amount
		if( rand( 0, 5 ) == 3 ) {
			sleep( rand( 2, 4 ) );
		}

		// User wasn't found, so the page wasn't!
		header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );

		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

?>
