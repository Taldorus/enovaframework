<?php		
	/*!
	* Bootstrap v1.2.1 (https://enovadev.com/)
	* Copyright 2021-2023 The Bootstrap Authors (https://https://github.com/Taldorus/enovaframework/contributors)
	* Licensed under exclusive copyright (https://https://github.com/Taldorus/enovaframework/master/LICENSE)
	*/
	
	require_once 'requete.php';
	require_once 'vue.php';
	
	class Routeur {
		// Route une requête entrante : exécute l'action associée
		public function routerRequete() {
			try {
				// Fusion des paramètres GET et POST de la requête
				$requete = new Requete(array_merge($_GET, $_POST));

				$controleur = $this->creerControleur($requete);
				$action     = $this->creerAction($requete);

				$controleur->executerAction($action);
			}
			catch (Exception $e) {
				$this->gererErreur($e);
			}
		}

		// Crée le contrôleur approprié en fonction de la requête reçue
		private function creerControleur(Requete $requete) {
			
			// INITIALISATION DU NOM DU CONTORLEUR
			$controleur = "Index";					
			if ($requete->existeParametre('url')) {
				$controleur = $requete->getParametre('url');
				// FORMATAGE DU NOM				
				$controleur = ucfirst(strtolower($controleur));
			}			
			$classeControleur  = "Controller" . $controleur;
			$fichierControleur = "controllers/" . $classeControleur . ".php";
			if (file_exists($fichierControleur)) {
				// Instanciation du contrôleur adapté à la requête
				require($fichierControleur);							
				$controleur = new $classeControleur();				
				// mémorisation de la requête dans le controleur
				$controleur->setRequete($requete);				
				// retour de l'instance
				return $controleur;
			}else{
				throw new Exception("Fichier '$fichierControleur' introuvable");
			}
		}

		// Détermine l'action à exécuter en fonction de la requête reçue
		private function creerAction(Requete $requete) {
			// Action par défaut
			$action = "index";
			if ($requete->existeParametre('action')) {
				$action = $requete->getParametre('action');
			}
			return $action;
		}

		// Gère une erreur d'exécution (exception)
		private function gererErreur(Exception $exception) {			
			$_POST = array();
			$_GET  = array('url' => 'error', 'msgErreur' => $exception->getMessage()); 
			$this->routerRequete();		
		}
	}
?>
