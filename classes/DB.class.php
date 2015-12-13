<?php

# Klassen DB är tänkt att bara användas som klass och inte skapa objekt av den
# Den har sin konstruktor och "clone" som privata så det inte går att göra objekt av den
# Utöver det har den en statisk metod getInstance() som alltså anropas på själva klassen
# samt en statisk property $instance som också lagras i själva klassen

# getInstance() returnerar alltid en koppling till databasen i form av ett mysqli-objekt
# det blir alltid samma uppkoppling och aldrig en ny under samma körning tack vare vår if-sats

class DB{

	private static $instance;

	private function __construct(){}
	private function __clone(){}

	public static function getInstance(){
		if(!self::$instance){ # Om vi redan har något i $instance i klassen (self::)
			# Skapa då ett mysqli-objekt med en kopplaing till vår databas och lagra den i $instance
			self::$instance = new mysqli("localhost","root","","phlogger");
			return self::$instance;
		}else{ # Om vi inte har något i $instance
			return self::$instance;
		}
	}
}