<?php

require_once "BDD.php";
require_once "formulaire.php";
require_once "champ.php";

class formulaire_BDD extends formulaire {

	private $name;
	private $id;
	private $description;
	private $champs;

	public function __construct($name) {
		$this->name = $name;
		$sql = "SELECT * FROM formulaire WHERE `name`='$name'";
		$connexion = new BDD();
		$connexion->requete($sql);
		$this->description = $connexion->retourne_ligne();
		$this->id = $this->description['id_formulaire'];
		unset($this->description['id_formulaire']);
//		var_dump($this->description);
		$sql = "SELECT * FROM champ WHERE `fk_champ_formulaire` = {$this->id}";
		$connexion->requete($sql);
		$this->champs = $connexion->retourne_tableau();
//		var_dump($this->champs);

		parent::__construct($this->description, $this->champs);
	}

	function __destruct() {
		$_SESSION['formulaire_BDD'] = serialize($this);
	}

	public function __toString() {
		return parent::__toString();
	}

}

?>
