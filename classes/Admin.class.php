<?php

class Admin{
	
	public static function login($params){
		
		if(isset($_POST['username'])){
			$mysqli = DB::getInstance();
			$username = $mysqli->real_escape_string($_POST['username']);
			$password = $mysqli->real_escape_string($_POST['password']);

			$password = crypt($password,'$2a$'.sha1($username));

			$query = "
				SELECT id
				FROM users
				WHERE username = '$username'
				AND password = '$password'
				LIMIT 1
			";

			$result = $mysqli->query($query);
			$user = $result->fetch_assoc();
			if($user['id']){
				$_SESSION['user']['id'] = $user['id'];
				$_SESSION['user']['name'] = $user['username'];
				return ['user' => $_SESSION['user']];
			}				
		}
		return [];
	}

}