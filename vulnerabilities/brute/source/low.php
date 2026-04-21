<?php

if( isset( $_GET[ 'Login' ] ) ) {
    // Get username
    $user = $_GET[ 'username' ];

    // Get password
    $pass = $_GET[ 'password' ];
    $pass = md5( $pass );

    // 1. Definiamo la query con i segnaposto
    $query  = "SELECT * FROM `users` WHERE user = ? AND password = ?";
    
    // 2. Prepariamo lo statement
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

    if ($stmt) {
        // 3. Colleghiamo le variabili ($user e $pass) ai due segnaposto. 
        // "ss" indica che sono due stringhe (String, String)
        mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
        
        // 4. Eseguiamo la query in modo sicuro
        mysqli_stmt_execute($stmt);
        
        // 5. Recuperiamo i risultati in un formato compatibile con il resto del codice
        $result = mysqli_stmt_get_result($stmt);

        if( $result && mysqli_num_rows( $result ) == 1 ) {
            // Get users details
            $row    = mysqli_fetch_assoc( $result );
            $avatar = $row["avatar"];

            // Login successful
            // Usiamo htmlspecialchars per evitare vulnerabilità XSS stampando l'username a video
            $safe_user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
            $html .= "<p>Welcome to the password protected area {$safe_user}</p>";
            $html .= "<img src=\"{$avatar}\" />";
        }
        else {
            // Login failed
            $html .= "<pre><br />Username and/or password incorrect.</pre>";
        }
        
        // Chiudiamo lo statement
        mysqli_stmt_close($stmt);
    } else {
        // Gestione errore se la preparazione fallisce
        die( '<pre>Database error: Unable to prepare statement.</pre>' );
    }

    ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

?>