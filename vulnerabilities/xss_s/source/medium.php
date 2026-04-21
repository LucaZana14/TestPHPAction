<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Get input
	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// Sanitize message input
	$message = strip_tags( addslashes( $message ) );
	$message = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $message ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$message = htmlspecialchars( $message );

	// Sanitize name input
	$name = str_replace( '<script>', '', $name );
	$name = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $name ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	// 1. Definiamo la query con i segnaposti '?' invece delle variabili
	$query = "INSERT INTO guestbook ( comment, name ) VALUES ( ?, ? );";
            
	// Recuperiamo la connessione globale
	$link = $GLOBALS["___mysqli_ston"];

	// 2. Prepariamo lo statement
	if ($stmt = mysqli_prepare($link, $query)) {
		
		// 3. Colleghiamo i parametri ("ss" significa due stringhe)
		// Questo neutralizza ogni tentativo di SQL Injection
		mysqli_stmt_bind_param($stmt, "ss", $message, $name);

		// 4. Eseguiamo la query
		$result = mysqli_stmt_execute($stmt);

		if (!$result) {
			// Gestione errore più pulita per compiacere Psalm
			die( '<pre>' . mysqli_error($link) . '</pre>' );
		}

		// 5. Chiudiamo lo statement
		mysqli_stmt_close($stmt);
	} else {
		die( '<pre>' . mysqli_error($link) . '</pre>' );
	}
}

?>
