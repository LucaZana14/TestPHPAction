<?php

if (array_key_exists("redirect", $_GET) && $_GET['redirect'] != "") {
    
    $scelta_utente = $_GET['redirect'];

    # La "Allowlist": CodeQL legge questo array e capisce che le stringhe sono sicure
    # perché le hai scritte tu, non l'utente.
    $destinazioni_sicure = [
        'info'  => 'info.php',
        'low'   => '/vulnerabilities/open_redirect/source/low.php',
        'home'  => '/index.php'
    ];

    # Se l'utente inserisce una chiave valida (es. "info")...
    if (array_key_exists($scelta_utente, $destinazioni_sicure)) {
        # ...facciamo il redirect usando la stringa dell'array.
        # Catena spezzata = Zero errori SAST!
        header("location: " . $destinazioni_sicure[$scelta_utente]);
        exit;
    } else {
        http_response_code(500);
        echo "<p>Destinazione non autorizzata.</p>";
        exit;
    }
}

http_response_code(500);
echo "<p>Missing redirect target.</p>";
exit;
?>