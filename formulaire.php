<?php

require_once "champ.php";

class formulaire {

	private $description;
	private $champs;

	public function __construct($description, $champs) {
		
		// 2 exemples de contrôles sur le tableau description
		
		// Contrôle de l'action pour rendre compatible W3C
		if(empty($description['action'])) {
			$description['action']=$_SERVER['PHP_SELF'];
		}

		// Contrôle de la méthode d'envoi du formulaire
		$description['method'] = strtoupper($description['method']);
		switch($description['method']) {
			case 'POST':
			case 'GET':
				break;
			default:
				$description['method']='POST';
		}

		// On stocke le tableau dans l'instance de formulaire
		$this->description = $description;

		// On mémorise la liste des champs localement à l'instance
		foreach ($champs as $champ) {
			$this->champs[] = new champ($champ);
		}
	}

	function __destruct() {
		$_SESSION['formulaire'] = serialize($this);
	}

	public function __toString() {
		$ret = "
		<form 
		method='{$this->description['method']}' 
		action='{$this->description['action']}' 
		name='{$this->description['name']}' 
		id='{$this->description['id']}' 
		onsubmit='{$this->description['onsubmit']}'>
		";
		$ret .= "
		<fieldset>
			<legend>{$this->description['legend']}</legend>
		";
		// Insertion des champs de formulaire
		foreach($this->champs as $champ) {
			$ret .= $champ;
		}
		$ret .= "
		</fieldset>
		</form>

";
		return $ret;
	}

	public function Controle_form() {
		$erreur = true; // Si premier chargement du formulaire
		if(!empty($_POST)) {
			$erreur = false; // On suppose que le formulaire est bon
			foreach($this->champs as $champ) {
				// Sauf s'il y a une erreur
				$erreur = $champ->Controle_champ() || $erreur;
			}
		}
		// On retourne vrai s'il y a une erreur
		return $erreur;
	}
}

?>
