<?php
	/*!
	* Bootstrap v1.2.1 (https://enovadev.com/)
	* Copyright 2021-2022 The Bootstrap Authors (https://https://github.com/Taldorus/enovaframework/contributors)
	* Licensed under MIT (https://https://github.com/Taldorus/enovaframework/master/LICENSE)
	*/
	
	// INITIALISATION DE LA SESSION
	session_start();

	class Vue {

		private $template;
		private $titre;
		private $styles;
		private $header;
		private $body;
		private $scripts;

		public function __construct($template, $action, $controleur, $titre) {
		  	// GERNERATION DU PATH DE LA VUE
			$path = $this->getTranslationFolder();
			if ($controleur != "") {
			  $path = $path . $controleur . "/";
			}

			// GENERATION DE LA SECTION "styles"
			$this->styles = $path . "css.php";
			if (!file_exists($this->styles)) {
				$this->styles = null;
			}

			// GENERATION DE LA SECTION "header"
			$this->header = $path . "header.php";
			if (!file_exists($this->header)) {
				$this->header = null;
			}

			// GENERATION DE LA SECTION "body"
			$this->body = $path . $action . ".php";

			// GENERATION DE LA SECTION "scripts"
			$this->scripts = $path . "js.php";
			if (!file_exists($this->scripts)) {
				$this->scripts = null;
			}

			$this->titre = $titre;
			$this->template = $template;
		}

		// Génère et affiche la vue
		public function generer($donnees) {
			// GERNERATION DES "styles"
			if ($this->styles != null){
				$styles = $this->genererFichier($this->styles, array());
			}else{
				$styles = "";
			}

			// RENERATION DU "header"
			if ($this->header != null){
				$header = $this->genererFichier($this->header, array());
			}else{
				$header = "";
			}

			// RENERATION DU "body"
			$body = $this->genererFichier($this->body, $donnees);

			// GERNERATION DES "scripts"
			if ($this->scripts != null){
				$scripts = $this->genererFichier($this->scripts, array());
			}else{
				$scripts = "";
			}

			// Génération du gabarit commun utilisant la partie spécifique
			$sourcePath = $this->getTranslationFolder();
			$templateName = $this->template.'.php';
			$vue = $this->genererFichier($sourcePath.$templateName, array('titre' => $this->titre, 'styles' => $styles , 'header' => $header, 'body' => $body , 'scripts' => $scripts));

			// Renvoi de la vue au navigateur
			echo $vue;
		}

		// Génère un fichier vue et renvoie le résultat produit
		private function genererFichier($fichier, $donnees) {
			if (file_exists($fichier)) {
				// Rend les éléments du tableau $donnees accessibles dans la vue
				if ($donnees != null) extract($donnees);
				// Démarrage de la temporisation de sortie
				ob_start();
				// Inclut le fichier vue
				// Son résultat est placé dans le tampon de sortie
				require $fichier;
				// Arrêt de la temporisation et renvoi du tampon de sortie
				return ob_get_clean();
			}else{
				throw new Exception("Fichier '$fichier' introuvable");
			}
		}

		private function getTranslationFolder(){
			$viewFolder = "views/";
			$allowed_domaine = array('enovadev.com', 'fr.enovadev.com', 'it.enovadev.com', 'de.enovadev.com', 'es.enovadev.comm','ru.enovadev.com','jp.enovadev.com', 'en.enovadev.com');
			if (isset($_SERVER['HTTP_HOST'])&&($_SERVER['HTTP_HOST']!='')){
				if (in_array($_SERVER['HTTP_HOST'], $allowed_domaine)){
					$DomainName = explode('.', $_SERVER['SERVER_NAME']);
					if (count($DomainName) == 3){
						switch($DomainName[0]){
							case 'fr':
								$viewFolder = "views.fr/";
								break;
							case 'es':
									$viewFolder = "views.es/";
									break;
							case 'it':
								$viewFolder = "views.it/";
								break;
							case 'de':
								$viewFolder = "views.de/";
								break;
							case 'ru':
									$viewFolder = "views.ru/";
									break;
							case 'jp':
									$viewFolder = "views.jp/";
									break;	
							case 'en':
							default:
								$viewFolder = "views/";
								break;
						}
					}
				}
			}
			return $viewFolder;
		}
	}
?>
