<?php	
	/*!
	* Bootstrap v1.2.1 (https://enovadev.com/)
	* Copyright 2021-2022 The Bootstrap Authors (https://https://github.com/Taldorus/enovaframework/contributors)
	* Licensed under MIT (https://https://github.com/Taldorus/enovaframework/master/LICENSE)
	*/
	
	require_once 'config/config_db.php';

	abstract class Model{

		private $LogPath;
		private $pdo;
		
		function Open_DB($LogPath){
			ini_set('memory_limit', '-1');
			$this->LogPath = $LogPath;
			$this->Connect_DB();			
		}
	
		function Close_DB(){
			$this->Disconnect_DB();
		}
		
		function Connect_DB(){
			try {
				$this->pdo = new PDO(
								"mysql:host=".DB_HOST.";dbname=".DB_DB.";charset=utf8",
								DB_USER,
								DB_PASS,
								[
									PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
									PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
									PDO::ATTR_EMULATE_PREPARES => false,
								]
							);
			}catch(PDOException $e) {
				$this->Log_DBError("db_connect", $e->getMessage(), $this->LogPath);
			}
		}
		
		function Disconnect_DB(){
			if ($this->pdo!==null) { $this->pdo = null; }
		}

		function SqlSelect($table, $colonnes, $conditions = null, $values = null){
			try{
				$query = "SELECT ".$colonnes." FROM ".$table;
				if ($conditions != null) $query .= " WHERE ".$conditions;
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				if ($req){
					$resultat = $prep->fetchAll();
					$prep->closeCursor();
					$prep = NULL;
					return $resultat;
				}else{
					$prep->closeCursor();
					$prep = NULL;
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}
		
		function SqlSelectPaged($table, $colonnes, $conditions = null, $values = null, $start, $length, $orderby = null){
			try{
				$query = "SELECT ".$colonnes." FROM ".$table;
				if ($conditions != null) $query .= " WHERE ".$conditions;
				if ($orderby != null) $query .= " ORDER BY ".$orderby;				
				$query .= " LIMIT ".$start.",".$length;
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				if ($req){
					$resultat = $prep->fetchAll();
					$prep->closeCursor();
					$prep = NULL;
					return $resultat;
				}else{
					$prep->closeCursor();
					$prep = NULL;
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}
		
		function SqlCount($table, $conditions = null, $values = null){
			try{								
				$query = "SELECT count(*) AS value_sum FROM ".$table;
				if ($conditions != null) $query .= " WHERE ".$conditions;
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				if ($req){
					$resultat = $prep->fetchAll();
					$prep->closeCursor();
					$prep = NULL;
					return $resultat[0]["value_sum"];
				}else{
					$prep->closeCursor();
					$prep = NULL;
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}

		function SqlInsert($table, $colonnes, $indexs, $values){
			try{
				$query = "INSERT INTO ".$table." (".$colonnes.") VALUES (".$indexs.")";
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				$prep->closeCursor();
				$prep = NULL;
				if ($req){
					return true;
				}else{
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}
		
		function SqlUpdate($table, $colonnes, $conditions, $values){
			try{
				$query = "UPDATE ".$table." SET ".$colonnes." WHERE ".$conditions;
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				$prep->closeCursor();
				$prep = NULL;
				if ($req){
					return true;
				}else{
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}

		function SqlDelete($table, $conditions, $values){
			try{
				$query = "DELETE FROM ".$table." WHERE ".$conditions;
				$prep = $this->pdo->prepare($query);
				$req = $prep->execute($values);
				$prep->closeCursor();
				$prep = NULL;
				if ($req){
					return true;
				}else{
					$this->Log_DBError($query, $resultat, $this->LogPath);
				}
			}catch(PDOException $e){
				$this->Log_DBError($query, $e, $this->LogPath);
			}
		}		
		
		function Log_DBError($SqlQuery, $resultat){
			$RappError  = "DATE    : ".date ("Y-m-d H:i:s")."<br>\r\n";
			$RappError .= "FICHIER : ".$this->LogPath."<br>\r\n";
			$RappError .= "REQUETE : ".$SqlQuery."<br>\r\n";
			$RappError .= "DEBUG   : ".$resultat."<br>\r\n";
			$RappError .= "<br>\r\n";
			error_log($RappError, 3, $this->LogPath);
			return array("status" => false,	"message" => "Internal error has occurred".$this->LogPath);
			exit();
		}
		
		function GetPdoResultat($Row, $Index, $NomColonne){
			if ($Row != null) {
				if ((!isset($Row[$Index][$NomColonne]))||(empty($Row[$Index][$NomColonne]))){
					return 0;
				}else{
					return $Row[$Index][$NomColonne];
				}
			}else{
				return 0;
			}
		}
	}
?>