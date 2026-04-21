<?php
header("Content-Type: application/json; charset=UTF-8");

if (array_key_exists ("callback", $_GET)) {
	$callback = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['callback']);
} else {
	return "";
}

$outp = array ("answer" => "15");

// Permetti solo caratteri alfanumerici e underscore
if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback)) {
    $callback = 'defaultCallback'; // Forza un nome sicuro se l'input è sospetto
}

echo $callback . "(" . json_encode($outp) . ")";
?>
