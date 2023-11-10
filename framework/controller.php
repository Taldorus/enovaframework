<?php		
	/*!
	* Bootstrap v1.2.1 (https://enovadev.com/)
	* Copyright 2021-2022 The Bootstrap Authors (https://https://github.com/Taldorus/enovaframework/contributors)
	* Licensed under MIT (https://https://github.com/Taldorus/enovaframework/master/LICENSE)
	*/
	
	abstract class Controleur {

		// Action à réaliser
		private $action;

		// Requête entrante
		protected $requete;
		
		// Définit la requête entrante
		public function setRequete(Requete $requete) {
			$this->requete = $requete;
		}
		
		// Récupere les données post json 
		public function getPostData() {			
			// récupere les données "post"
			$json = file_get_contents('php://input');	
			// décodage json
			$myJSON = json_decode($json, true);
			// traitement des erreurs
			if (json_last_error() == JSON_ERROR_NONE){
				return array("status" => true, "data" => $myJSON);
			}else{
				return array("status" => false);
			}					
		}
		
		// Récupere les données get  
		public function getGetData(){
			if ($this->requete->existeParametre('m')) {
				return array("status" => true, "data" => $this->requete->getParametre('m'));				
			}else{
				return array("status" => false);				
			}
		}
		
		// Exécute l'action à réaliser
		public function executerAction($action) {
			if (method_exists($this, $action)) {
				$this->action = $action;
				$this->{$this->action}();
			}else {
				$classeControleur = get_class($this);
				throw new Exception("Action '$action' non définie dans la classe $classeControleur");
			}
		}

		// Méthode abstraite correspondant à l'action par défaut
		// Oblige les classes dérivées à implémenter cette action par défaut
		public abstract function index();

		// Génère la vue associée au contrôleur courant	
		protected function genererVue($TemplateData) {		
			// Détermination du nom du fichier vue à partir du nom du contrôleur actuel
			$classeControleur = get_class($this);
			$controleur = str_replace("Controller", "", $classeControleur);
			// Instanciation et génération de la vue
			$vue = new Vue($TemplateData['template'], $this->action, $controleur, $TemplateData['titre']);
			$vue->generer($TemplateData['data']);
		}
	}
?>