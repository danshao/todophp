<?php

    require "connect.php";
    require "todo.php";
    require "completed.php";

    // Select todos, ordered by position:
    $query = mysql_query("SELECT * FROM `todo` WHERE `completed` != 1 ORDER BY `position` ASC");
    $todos = array();
    while($row = mysql_fetch_assoc($query)){
    	$todos[] = new ToDo($row);
    }

    // Select completed, ordered by position:
    $query = mysql_query("SELECT * FROM `todo` WHERE `completed` != 0 ORDER BY `position` ASC");
    $completed = array();
    while($row = mysql_fetch_assoc($query)){
    	$completed[] = new Completed($row);
    }
?>

<!DOCTYPE html>
    <head>
        <title>To Do List</title>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="styles.css" />
    </head>

    <body>

    <h1>To Do List</h1>
    <div id="main">
    	<ul class="todoList">	
            <?php
    		foreach($todos as $item){
    			echo $item;
    		}	
    		?>
        </ul>
        <a id="addButton" class="addbutton" href="#">ADD</a>
    </div>

    <h1>Completed</h1> 
    <div id="completed">
    	<ul class="completedList">
    		<?php
    			foreach($completed as $complete){
    				echo $complete;
    			}
    			?>
    	</ul>
    </div>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script.js"></script>

    </body>
</html>
