<?php 
$string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
$brut = json_decode($string, true);
$top = $brut["feed"]["entry"];
var_dump($top[0]);
function displayTop($top){
	foreach ($top as $key => $value) {
		echo "<li>". intval($key+1 ). " : " . $value["im:name"]["label"] . "</li>";
	}
}
function searchRanking($needle, $haystack){
	foreach ($haystack as $key => $value) {
		if($value["im:name"]["label"] === $needle){
			return(intval($key + 1));
		}	
	}
}
function searchDirector($director, $haystack){
	foreach ($haystack as $key => $value) {
		if($value["im:name"]["label"]=== $director){
			return($value["im:artist"]["label"]);		
		}
	}
}
function moviesBefore($year, $haystack){
	$count = 0;
	foreach ($haystack as $key => $value) {
		if(strtotime(str_replace(","," ", $value["im:releaseDate"]["attributes"]["label"])) < strtotime("january" . $year)){
			$count++;
		}
	}
	return($count);
}
function sortMoviesByRelease($haystack){
	$arrayDates = [];
	foreach ($haystack as $key => $value) {
		$arrayDates[$value["im:name"]["label"]] = strtotime(str_replace(","," ", $value["im:releaseDate"]["attributes"]["label"]));
	}
	asort($arrayDates);
	foreach ($arrayDates as $key => $value) {
		$arrayDates[$key] = date("Y-m-d",$value);	
	}
	return($arrayDates);
}

function arrayOfType($haystack){
	$arrayType = [];
	foreach ($haystack as $key => $value) {
		$thisType = $value["category"]["attributes"]["label"];
		if(array_key_exists($thisType, $arrayType)){	
			$arrayType[$thisType]++;
		}else{
			$arrayType[$thisType] = 1;
		}
	}
	asort($arrayType);
	return($arrayType);
}

function arrayOfDirector($haystack){
	$arrayType = [];
	foreach ($haystack as $key => $value) {
		$thisDirector= $value["im:artist"]["label"];
		if(array_key_exists($thisDirector, $arrayType)){	
			$arrayType[$thisDirector]++;
		}else{
			$arrayType[$thisDirector] = 1;
		}
	}
	asort($arrayType);
	return($arrayType);
}

function cumulativePrices($haystack){
	$buyPrice = 0;
	$rentalPrice = 0;
	for ($i=0; $i < 10; $i++) { 
		$price = $haystack[$i]["im:price"]["label"];
		if(isset($haystack[$i]["im:rentalPrice"]["label"])){
			$rentalPrice+= floatval(str_replace("$", "",$haystack[$i]["im:rentalPrice"]["label"]));
		}
		$buyPrice += floatval(str_replace("$", "",$price));
	}
	echo "Achat : "	. strval($buyPrice) . "$. Location : " . strval($rentalPrice) . "$";
}

function makePriceArray($haystack){
	$priceArr = []; 
	foreach ($haystack as $key => $value) {
		$priceArr[$value["im:name"]["label"]] = intval(floatval($value["im:price"]["attributes"]["amount"]) * 100);
	}
	return($priceArr);
}
function bestCheapMovies($haystack,$nbrOfMoviesWanted){
	$bestArr = [];
	$LenghtBest = 0;
	$priceArr = makePriceArray($haystack);
	$sortedClone = $priceArr;
	asort($sortedClone);
	$lowestPrice = current($sortedClone);
	$highestPrice = end($sortedClone);
	while ($lowestPrice <= $highestPrice){
		foreach ($priceArr as $key => $value) {
			if($LenghtBest === $nbrOfMoviesWanted){
				return($bestArr);
			}elseif($value === $lowestPrice){
				$bestArr[$key] = $value;
				$priceArr[$key] = NULL;
				$LenghtBest++;
			}
		} 	
		$lowestPrice ++;
	}
	echo "PROBLEM";
	return($bestArr);

}

?>
<div>
	<h2>Films Exercices:</h2>
	<h3>Classement: </h3>
	<h4><ul><?php displayTop($top); ?></ul></h4>	
</div>
<div>
	<h3>
		Quel est le classement du film « Gravity » ?
	</h3>
	<h4>
		Il est classé : <?php echo searchRanking("Gravity", $top)  ?>
	</h4>
</div>
<div>
	<h3>
		Quel est le réalisateur du film « The LEGO Movie » ?
	</h3>
	<h4>
		Il s'agit de : <?php echo searchDirector("The LEGO Movie", $top); ?>
	</h4>
</div>
<div>
	<h3>
		Combien de films sont sortis avant 2000 ?
	</h3>
	<h4>
		<?php echo moviesBefore("2000", $top); ?>
	</h4>
</div>
<div>
	<h3>
		Quel est le film le plus récent ? Le plus vieux ?
	</h3>
	<h4>
		<?php 
		$sortedArray = sortMoviesByRelease($top);
		$keys = array_keys($sortedArray);
		echo $keys[0] . " le " . $sortedArray[$keys[0]] ;
		echo " et ";
		echo end($keys) . " le " . $sortedArray[end($keys)];
		?>
	</h4>
</div>
<div>
	<h3>
		Quelle est la catégorie de films la plus représentée ?
	</h3>
	<h4>
		<?php
		$types = ArrayOfType($top);
		end($types);
		$mostRepresented = key($types);
		echo $mostRepresented .  " avec " . strval(end($types)) . " films";
		?>
	</h4>
</div>

<div>
	<h3>
		Quel est le réalisateur le plus présent dans le top100 ?
	</h3>
	<h4>
		<?php
		$types = arrayOfDirector($top);
		end($types);
		$mostRepresented = key($types);
		echo $mostRepresented .  " avec " . strval(end($types)) . " films";
		?>
	</h4>
</div>
<div>
	<h3>
		Combien cela coûterait-il d'acheter le top10 sur iTunes ? de le louer ?
	</h3>
	<h4>
		<?php
		cumulativePrices($top);
		?>
	</h4>
</div>
<div>
	<h3>
		Quels sont les 10 meilleurs films à voir en ayant un budget limité ?
	</h3>
	<h4>
		<?php

			$arrayBestCheapMovies = bestCheapMovies($top, 10);
			foreach ($arrayBestCheapMovies as $key => $value) {
				echo "$key, ";
			}
			echo ".";
		?>
	</h4>
</div>