<?php
	include FILE_ADMIN_PANEL_SETTINGS;
?>

@include('app.not-logged.html_start', 	array("globalPreferences"=>$globalPreferences))
@include('app.not-logged.head', 		array("globalPreferences"=>$globalPreferences))
@include('app.not-logged.body', 		array("globalPreferences"=>$globalPreferences))