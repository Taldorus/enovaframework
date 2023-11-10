<?php
	/*!
	* Bootstrap v1.2.1 (https://enovadev.com/)
	* Copyright 2021-2022 The Bootstrap Authors (https://https://github.com/Taldorus/enovaframework/contributors)
	* Licensed under MIT (https://https://github.com/Taldorus/enovaframework/master/LICENSE)
	*/
	
	class Requete {
		// paramètres de la requête
		private $parametres;

		public function __construct($parametres) {
			$this->parametres = $parametres;
		}

		// Renvoie vrai si le paramètre existe dans la requête
		public function existeParametre($nom) {
			return (isset($this->parametres[$nom]) && $this->parametres[$nom] != "");
		}

		// Renvoie la valeur du paramètre demandé OU Lève une exception si le paramètre est introuvable
		public function getParametre($nom) {
			if ($this->existeParametre($nom)) {
				return $this->parametres[$nom];
			}else{
				throw new Exception("Paramètre '$nom' absent de la requête");
			}
		}
	}
?>