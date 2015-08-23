<?php

class ToDo {
	
	private $data;
	
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
		
	public function __toString(){
		return '
			<li id="todo-'.$this->data['id'].'" class="todo">
			
				<div class="text">'.$this->data['text'].'</div>
				
				<div class="actions">
					<a href="#" class="edit">Edit</a>
					<a href="#" class="complete">Complete</a>
					<a href="#" class="delete">Delete</a>
				</div>
				
			</li>';
	}
	
	// Edit text 	
	public static function edit($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE todo
						SET text='".$text."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}
	
	// Delete text 
	public static function delete($id){
		
		mysql_query("DELETE FROM todo WHERE id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	public static function complete($id){
		mysql_query("	UPDATE todo
						SET completed=1
						WHERE id=".$id
					);
	
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't complete item!");	
	}
	
	// Rearrange item 
	public static function rearrange($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");

		mysql_query("	UPDATE todo SET position = CASE id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	// Create new item 
	public static function createNew($text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM todo");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		mysql_query("INSERT INTO todo SET text='".$text."', position = ".$position);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting TODO!");
		
		echo (new ToDo(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		exit;
	}
	
	// Sanitize string
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} 

?>