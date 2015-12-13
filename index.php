<?php
session_start();
require_once("classes/DB.class.php");

# $url_params blir en array med alla "värden" som står efter ? avgränsade med /
# ex. /Posts/single/11 kommer ge en array med 3 värden som är Posts, single och 11
$url_parts = getUrlParts($_GET); 
$class = array_shift($url_parts); # tar ut första värdet och lägger den i $class, i vårt exempel ovan "Posts"
$method = array_shift($url_parts); # tar ut andra värdet och lägger den i $method, i vårt exempel ovan "single"

# Hämta in klassfilen för den klass vi ska anropa
require_once("classes/".$class.".class.php"); 

# Anropa metoden vill vill köra på klassen vi har fått från vår URL 
# samt skicka med övriga parametrar in till den metoden, i vårt exempel ovan finns "11" kvar
# Svaret från anropet av metoden, dvs det den kör return på, lagrar vi i $data
$data = $class::$method($url_parts); 


if(isset($data['redirect'])){ # om $data innehåller något på nyckeln 'redirect'
	
	# header() sätter en HTTP-header. I det här faller 'Location: ' 
	# som säger åt webbläsaren att ladda en annan sida istället
	# Sidan vi ber den ladda är innehållet vi fick i $data['redirect']
	# vilket alltså bestämdes av den klass och metod vi anropade
	header("Location: ".$data['redirect']); 

}else{ # om $data INTE innehåller något på nyckeln 'redirect'
	
	# startTwig() ligger längre ner i den här filen och kör de rader kod vi behöver för att starta Twig
	$twig = startTwig();

	if($class == 'Admin'){	# om klassen vi laddat var Admin
		$template = "Admin/index.html";
	}else{ 					# om klassen vi laddat var något annat än Admin
		$template = 'index.html';
	}

	# låt Twig rendera den template vi pekat ut ovan och skicka med den $data 
	# som vi fick från metoden vi anropade
	echo $twig->render($template, $data);
}




###############################################################
####################    FUNCTIONS    ##########################
###############################################################

# Funktion som "slår sönder" det vi får efter ? på alla /
# och skickar tillbaka som en array
function getUrlParts($get){

	# Eftersom vi vill ha en snygg URL skickar vi något i stil med: ?/Posts/all
	# det blir då nyckeln på en GET-parameter istället för värdet
	# detta enligt principen ?nyckel=värde&nyckel2=värde
	# och då vi inte skickar något = kommer allt efter ? bli vår nyckel
	# dvs i vårt exempel kommer vi ha en $_GET['/Posts/all']

	# För att få ut nyckeln som ett värde istället använder vi array_keys()
	# den tar alla nycklar i en array och gör dem till värden i en ny array
	# i det här faller tar vi $get som vi fick in i funktionen och som från början var $_GET
	# sen lagrar vi det värdet i $_get_params
	$get_params = array_keys($get);
	
	# eftersom vi bara skickat en nyckel i vår URL ligger den på position 0 i vår nya array
	# vi plockar ut det värdet till $url som nu kommer innehålle en string med värder "/Posts/all" enligt vårt exempel
	$url = $get_params[0];

	# det vi vill göra nu är att slå sönder denna på alla / så vi kan ta varje ord för sig
	# jag vill i vårt exempel alltså få ur "Posts" och "all" var för sig
	# därför använder vi explode() och delar vår string på alla "/" 
	$url_parts = explode("/",$url);
	
	# $url_parts är nu en array med ett värde för varje del som blev av vår string
	# när vi delade den på /
	# eftersom vi har ett initialt / och kanske även kan råka skriva för många / exempelvis /Posts//all/
	# så vill vi nu tvätta vår array så att vara orden finns kvar och inte tomma värden
	# /Posts/all kommer ge tre värden, först "" (dvs tomt) sen "Posts" och sen "all"

	# vi löser detta genom att loopa igenom hela vår array och sparar allt som innehåller ett ord i en ny $array
	foreach($url_parts as $k => $v){
		if($v) $array[] = $v; # om det finns ett innehåll på platsen vi är på just nu, spara det i $array
	}

	# sen stoppar vi tillbaks den tvättade arrayen i $url_parts
	$url_parts = $array;

	# och avslutar med att returnera vår tvättade array som nu innehåller:
	# [0] => "Posts", [1] => "all"
	return $url_parts; 
}


# Funktion som laddar in och kör igång Twig
# returnerar ett Twig objekt
function startTwig(){
	require_once('Twig/lib/Twig/Autoloader.php');
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('templates/');
	return $twig = new Twig_Environment($loader);
}