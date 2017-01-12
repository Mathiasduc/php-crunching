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