<?php

	require "connect.php";
	require "todo.php";
	require "completed.php";

	$id = (int)$_GET['id'];

	try{

		switch($_GET['action'])
		{
			case 'delete':
				ToDo::delete($id);
				break;
				
			case 'rearrange':
				ToDo::rearrange($_GET['positions']);
				break;
				
			case 'edit':
				ToDo::edit($id,$_GET['text']);
				break;
				
			case 'new':
				ToDo::createNew($_GET['text']);
				break;

			case 'complete':
				ToDo::complete($id);
				break;

			case 'undo':
				Completed::undo($id);
				break;
		}

	}
	catch(Exception $e){
		die("0");
	}

	echo "1";
?>

