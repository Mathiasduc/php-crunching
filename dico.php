<div>
	<?php 
	$string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
	$dico = explode("\n", $string);

	function is15Lenght($dico){
		$count = 0;
		foreach ($dico as $key => $value) {
			if(strlen($value) === 15){
				$count++;
			}
		}
		return $count;
	}

	function howManyW($dico){
		$caseFound = 0;
		foreach ($dico as $key => $value) {
			if (strpos($value, 'w')){
				echo " <span>".$value . ' / </span>';
				$caseFound++;
			}
		}
		return $caseFound;
	}

	function howManyQinLastPos($dico){
		$caseFound = 0;
		foreach ($dico as $key => $value) {
			if (strpos($value, 'q') === strlen($value)-1){
				echo 	$value . ' / ';
				$caseFound++;
			}
		}
		return $caseFound;
	}
	?>
	
	<h3>
		Combien de mots contient ce dictionnaire ?
	</h3>
	<h4>
		<?php echo count($dico); ?>	
	</h4>

	<h3>
		Combien de mots font exactement 15 caractères ?
	</h3>
	<h4>
		<?php echo is15Lenght($dico); ?>	
	</h4>

	<h3>
		Combien de mots contiennent la lettre « w » ?
	</h3>
	<h4>
	Les occurences de cette lettre sont dans les mots suivants:
	</h4>
	<?php 
	echo "<br> <h4>Et donc son au nombre de : " . howManyW($dico) . "</h4>";

	?>

	<h3>
		Combien de mots finissent par la lettre « q » ?
	</h3>
	<h4>Les occurences de ce cas sont dans les mots suivants:
		<?php 
		echo "<br>Et donc son au nombre de : " . howManyQinLastPos($dico);

		?>
		
	</h4>
</div>