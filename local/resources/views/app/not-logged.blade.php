<?php
	include FILE_ADMIN_PANEL_SETTINGS;
?>

@include('app.not-logged.html_start', 	array("globalSettings"=>$globalSettings))
@include('app.not-logged.head', 		array("globalSettings"=>$globalSettings))
@include('app.not-logged.body', 		array("globalSettings"=>$globalSettings))