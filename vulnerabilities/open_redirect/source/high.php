<?php

if (array_key_exists("redirect", $_GET) && $_GET['redirect'] != "") {
    
    $scelta_utente = $_GET['redirect'];

    # La nostra fidata Allowlist. Nel livello High, vogliono farti andare solo su info.php
    $destinazioni_sicure = [
        'info.php' => 'info.php'
    ];

    # Se l'utente ha scritto esattamente "info.php" (come chiave)...
    if (array_key_exists($scelta_utente, $destinazioni_sicure)) {
        # ...facciamo il redirect usando la NOSTRA stringa, non la sua.
        # Catena spezzata, Taint Analysis azzerata!
        header("location: " . $destinazioni_sicure[$scelta_utente]);
        exit;
    } else {
        http_response_code(500);
        ?>
        <p>You can only redirect to the info page.</p>
        <?php
        exit;
    }
}

http_response_code(500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>