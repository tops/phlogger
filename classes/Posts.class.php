<?php

/**
 * Det här är klassen Posts som anropas om man besöker ?/Posts/...
 * 
 * Alla metoder som ska gå att anropa genom URL:en är statiska och publika,
 * de tar också alla emot en array som de lagrar i $params under körningen av metoden.
 * Slutligen returnerar de alla en array som sedan blir det vi lägger i $data i index.php och skickar till Twig
 */

class Posts{

	/**
	 * Metoden all() hämtar alla inlägg i bloggen från databasen.
	 * Metoden skickar tillbaka en array med alla inlägg under nyckeln 'posts'
	 */
	public static function all($params){
		 $mysqli = DB::getInstance(); 						# Hämta uppkopplingen till databasen
		 $result = $mysqli->query("SELECT * FROM posts"); 	# Skicka wn SQL-fråga till databasen för att hämta alla inlägg

		 while($post = $result->fetch_assoc()){				# Loopa igenom resultatet med alla inlägg
		 	$posts[] = $post;								# Ta varje inlägg och lägg in det i arrayen $posts
		 }

		 return ['posts' => $posts];						# returnerar alla inläggen under nyckeln 'posts'
	}

	/**
	 * 
	 */
	public static function category($params){
		 $mysqli = DB::getInstance();

		 $id = $params[0];

		 if(is_numeric($id)){
		 	$query = "
			 SELECT *
			 FROM posts, categories
			 WHERE posts.category_id = categories.id
			 AND categories.id = $id
			 ";
		 }else{
		 	$query = "
			 SELECT *
			 FROM posts, categories
			 WHERE posts.category_id = categories.id
			 AND categories.name = '$id'
			 ";
		 }		 

		 $result = $mysqli->query($query);

		 while($post = $result->fetch_assoc()){
		 	$posts[] = $post;
		 }

		 return ['posts' => $posts];
	}

	/**
	 * 
	 */
	public static function single($params){
		$id = $params[0];
		$mysqli = DB::getInstance();
		$id = $mysqli->real_escape_string($id);
		$result = $mysqli->query("SELECT * FROM posts WHERE id=$id");
		$post = $result->fetch_assoc();

		$result = $mysqli->query("SELECT * FROM comments WHERE post_id=$id");
		
		while($comment = $result->fetch_assoc()){
			$comments[] = $comment;
		}	 	

	 	return ['post' => $post, 'comments' => $comments];  
		
	}

	/**
	 * 
	 */
	public static function create($params){

		if(isset($_POST['title'])){
			$mysqli = DB::getInstance();
			$title = $mysqli->real_escape_string($_POST['title']);
			$text = $mysqli->real_escape_string($_POST['text']);
		
			$query = "
				INSERT INTO posts 
				(title, text) 
				VALUES ('$title', '$text')
			";

			$mysqli->query($query);

			return ['newpost' => FALSE, 'message' => 'Nytt inlägg skapat!'];
		}

		return ['newpost' => TRUE];
	}
}