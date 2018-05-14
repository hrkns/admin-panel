<?php
	require_once (substr($_SERVER["SCRIPT_FILENAME"], 0, strpos($_SERVER["SCRIPT_FILENAME"], "sitio")))."sitio/root.php";

	if(REDIR_FORBIDDEN_DIRECTORY)
		header("Location: ".LOCATION_REDIR_FORBIDDEN_DIRECTORY);
?>
