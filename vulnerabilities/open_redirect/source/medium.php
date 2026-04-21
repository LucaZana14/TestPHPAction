<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
    $target = $_GET['redirect'];

    # LA VERA PROTEZIONE: Il path DEVE iniziare con un solo '/' 
    # e non deve contenere caratteri strani o il doppio '//'
    if (preg_match('/^\/[a-zA-Z0-9_\-\/\.]+$/', $target) && strpos($target, '//') === false) {
        header ("location: " . $target);
        exit;
    } else {
        http_response_code (500);
        ?>
        <p>Absolute URLs not allowed or invalid path.</p>
        <?php
        exit;
    }
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>