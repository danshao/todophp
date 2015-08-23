<?php

class Completed {

	private $data;

	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	public function __toString(){
		return '
			<li id="todo-'.$this->data['id'].'" class="completed">
			
				<div class="text">'.$this->data['text'].'</div>
				
				<div class="actions">
					<a href="#" class="undo">Undo</a>
				</div>
				
			</li>';
	}
		
	// Undo 
	public static function undo($id){
		mysql_query("	UPDATE todo
						SET completed=0
						WHERE id=".$id
					);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't undo item!");	
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

	// Sanitize string 
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} 

?>