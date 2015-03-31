<?php

class champ {

	private $id;
	private $label;
	private $tabindex;
	private $type;
	private $name;
	private $value;
	private $placeholder;
	private $class;
	private $javascript;
	private $checked;

	private $erreur;
	
	public function __construct($descr_champ) {

		$this->id = $descr_champ['id'];
		$this->label = $descr_champ['label'];
		$this->tabindex = $descr_champ['tabindex'];
		$this->type = $descr_champ['type'];
		$this->name = $descr_champ['name'];
		$this->value = $descr_champ['value'];
		$this->placeholder = $descr_champ['placeholder'];
		$this->class = $descr_champ['class'];
		$this->javascript = $descr_champ['javascript'];
		$this->checked = $descr_champ['checked'];
		$this->erreur = false;		
	}

	public function __toString() {

		switch($this->type) {
			case 'text':
			case 'email':
				$ret = "
				<label for='{$this->id}'>{$this->label}</label>
				<input tabindex='{$this->tabindex}' 
					type='{$this->type}' 
					name='{$this->name}' 
					value='{$this->value}' 
					placeholder='{$this->placeholder}' 
					class='{$this->class}' 
					id='{$this->id}' 
					{$this->javascript}/>";
				if($this->erreur) {
					$ret .="
					<span style='color:red'>{$this->erreur}</span>
					";
				}
				$ret .= "
				<br/>
";
				break;

			case 'textarea':
				$ret = "
			<label for='{$this->id}'>{$this->label}</label>
			<textarea tabindex='{$this->tabindex}' 
				name='{$this->name}'
				placeholder='{$this->placeholder}' 
				class='{$this->class}' 
				id='{$this->id}' 
				rows=5 
				cols=50 
				{$this->javascript}>
				{$this->value}
				</textarea>
			<br/>
";
				break;

			case 'checkbox':
			case 'radio':
				$ret = "
				<label for='{$this->id}'>{$this->label}</label>
				<input 
					tabindex='{$this->tabindex}' 
					type='{$this->type}' 
					name='{$this->name}' 
					value='{$this->value}'  
					class='{$this->class}' 
					id='{$this->id}'";
				if(!empty($this->checked))
					$ret .=" 
					checked='checked'
";
				$ret .="
				/>
";
				break;

			case 'select':
				$ret = "
			<label for='{$this->id}'>{$this->label}</label>
			<select tabindex='{$this->tabindex}' 
				name='{$this->name}' 
				class='{$this->class}' 
				id='{$this->id}'>\n";
				// Parcours des différentes valeurs du select
				foreach ($this->value as $value) {
					// Ajout de chaque valeur à la chaîne à retourner
					if(in_array($value, $this->checked)) {
						$ret.="
					<option value='$value' selected>$value</option>
						";
					} else {
						$ret.="
				<option value='$value'>$value</option>
						";
					}
				}
$ret.="
			</select>";
				break;

			case 'submit':
				$ret = "
				<input tabindex='{$this->tabindex}' 
					type='{$this->type}'";
				if(!empty($this->placeholder))
					$ret.="
					accesskey='{$this->placeholder}'";
				$ret .=" 
					name='{$this->name}' 
					value='{$this->value}' 
					class='{$this->class}' 
					id='{$this->id}' 
					{$this->javascript}/>
				<br/>
";
				break;

			default:
				$ret = "ERREUR DE TYPE DE CHAMP<br/>";
		}

		return $ret;

	}

	public function Controle_champ() {
		$this->erreur = false;
		switch($this->type) {
			case 'text':
				if(empty($_POST[$this->name])) {
					$this->erreur = "Merci de compléter.";
				}
			break;
			case 'email':
				if(empty($_POST[$this->name]) || filter_var($_POST[$this->name], FILTER_VALIDATE_EMAIL)) {
					$this->erreur = "Email non valide";
				}
			break;
		}
		return $this->erreur;
	}
}

?>
