<?php

	// Set DB variables here
	$db_host		= '0.0.0.0';
	$db_user		= 'root';
	$db_pass		= 'root';
	$db_database	= 'todo'; 

	$link = @mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

	mysql_set_charset('utf8');
	mysql_select_db($db_database,$link);
?>