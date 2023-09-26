<?php
class categories{
    //Connexion
    private $connexion;
    private $table = "categories"; //table dans la base de données

    //Propriétés
    public $idcategories;
    public $nom;

/**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

/**
     * Lecture des categories (toutes)
     *
     *
     */
    public function lire(){
        try{
            //on écrit la requête sql *$this table permet d'aller dans la table definis au debut du code
            $sql = "SELECT idcategories, nom FROM " . $this->table;
            //on prepare la requête
            $query = $this->connexion->prepare($sql);
            $query->execute();
            //on retourne le resultat
            return $query;
        }catch (PDOException $e) {
            // Gestion des erreurs de base de données
            return false;
        }
    }

    /**
     * Créer une categorie
     *
     * 
     */

    public function create(){
        try{
            //ecriture de la requête sql
            $sql = " INSERT INTO " . $this->table . "SET nom=:nom";
            //préparer la requête
            $query = $this->connexion->prepare($sql);

            //protection contre les injections
            $this->nom=htmlspecialchars(strip_tags($this->nom));

            //ajout des données protégées
            $query->bindParam(":nom", $this->nom);

            //execution de la requête
            if($query->execute()){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            // Gestion des erreurs de base de données
            return false;
        }
    }
/**
     * Mettre à jour une categorie
     *
     * 
     */
    public function update(){
        try{
            //ecriture de la requête sql mettre à jour une catégorie par son ID
            $sql = "UPDATE " . $this->table . " SET nom = :nom WHERE idcategories = :idcategories";

            //préparer la requête
            $query = $this->connexion->prepare($sql);
            //protection contre les injections
            $this->nom=htmlspecialchars(strip_tags($this->nom));

            //ajout des données protégées
            $query->bindParam(":nom", $this->nom);
            $query->bindParam(":idcategories", $this->idcategories);
            //execution de la requête
            if($query->execute()){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            // Gestion des erreurs de base de données
            return false;
        }
    }
   // Fonction pour définir l'ID
public function setId($id)
{
    $this->idcategories = $id;
}
/**
 * Lecture des categories (id)
 *
 * 
 */
public function lireId(){
    try{
        $sql = "SELECT idcategories, nom FROM " . $this->table . " WHERE idcategories = ? LIMIT 0,1";
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
//      echo"SQL Query: " . $sql;
        $query->bindParam(1, $this->idcategories);
        $query->execute();

        //on retourne le resultat
        return $query;
        }catch (PDOException $e){
            // Gestion des erreurs de base de données
            error_log("Erreur PDO dans la méthode lireId(): " . $e->getMessage());
            
            return false;
        }
    }
    
}