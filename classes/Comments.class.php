<?php

class Comments{
	
	public static function create(){

		if(isset($_POST['name'])){
			$mysqli = DB::getInstance();
			$name = $mysqli->real_escape_string($_POST['name']);
			$text = $mysqli->real_escape_string($_POST['text']);
			$post_id = $mysqli->real_escape_string($_POST['post_id']);

			$query = "
				INSERT INTO comments 
				(name, text, post_id) 
				VALUES ('$name', '$text', $post_id)
			";

			$mysqli->query($query);

			return ['redirect' => $_SERVER['HTTP_REFERER']];
		}

	}

}