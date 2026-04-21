<?php

if (array_key_exists("redirect", $_GET) && $_GET['redirect'] != "") {
    
    $scelta_utente = $_GET['redirect'];

    # La nostra "Allowlist": CodeQL legge questo array e capisce che le stringhe sono sicure
    $destinazioni_sicure = [
        'info'  => 'info.php',
        'medium' => '/vulnerabilities/open_redirect/source/medium.php',
        'home'  => '/index.php'
    ];

    # Verifichiamo se l'input dell'utente esiste come CHIAVE nel nostro array
    if (array_key_exists($scelta_utente, $destinazioni_sicure)) {
        # Facciamo il redirect usando la stringa sicura dell'array
        header("location: " . $destinazioni_sicure[$scelta_utente]);
        exit;
    } else {
        # Se prova a inserire "http://hacker.com", non lo troverà nell'array e verrà bloccato
        http_response_code(500);
        echo "<p>Destinazione non autorizzata.</p>";
        exit;
    }
}

http_response_code(500);
echo "<p>Missing redirect target.</p>";
exit;
?>