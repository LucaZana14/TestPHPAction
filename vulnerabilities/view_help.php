<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'Help' . $page[ 'title_separator' ].$page[ 'title' ];

if (array_key_exists ("id", $_GET) &&
	array_key_exists ("security", $_GET) &&
	array_key_exists ("locale", $_GET)) {
	$id       = basename($_GET[ 'id' ]);
	$security = basename($_GET[ 'security' ]);
	$locale = basename($_GET[ 'locale' ]);

	if (!preg_match('/^[a-zA-Z0-9_-]+$/', $id)) {
        die("Security Alert: Invalid ID format.");
    }

	if (!preg_match('/^[a-zA-Z0-9_-]+$/', $security)) {
        die("Security Alert: Invalid ID format.");
    }

	if (!preg_match('/^[a-zA-Z0-9_-]+$/', $locale)) {
        die("Security Alert: Invalid ID format.");
    }

	ob_start();
	if ($locale == 'en') {
		eval( '?>' . file_get_contents( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/help.php" ) . '<?php ' );
	} else {
		eval( '?>' . file_get_contents( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/help.{$locale}.php" ) . '<?php ' );
	}
	$help = ob_get_contents();
	ob_end_clean();
} else {
	$help = "<p>Not Found</p>";
}

$page[ 'body' ] .= "
<script src='/vulnerabilities/help.js'></script>
<link rel='stylesheet' type='text/css' href='/vulnerabilities/help.css' />

<div class=\"body_padded\">
	{$help}
</div>\n";

dvwaHelpHtmlEcho( $page );

?>
