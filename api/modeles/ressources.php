<?php
class ressources{
    //Connexion
    private $connexion;
    private $table = "ressources"; //table dans la base de données

    //Propriétés objets
    public $id_ressources;
    public $url_ressource;


     /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

 /**
     * Lecture des ressource (toutes)
     *
     * 
     */
    public function lire(){
        try{
            //on écrit la requête sql *$this table permet d'aller dans la table definis au debut du code
            $sql = "SELECT id_ressources, url_ressource FROM " . $this->table;
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
     * Créer une ressource
     *
     * 
     */

    public function create(){
        try{
            //ecriture de la requête sql
            $sql = " INSERT INTO " . $this->table . "SET url_ressource=:url_ressource";
            //préparer la requête
            $query = $this->connexion->prepare($sql);

            //protection contre les injections
            $this->url_ressource=htmlspecialchars(strip_tags($this->url_ressource));

            //ajout des données protégées
            $query->bindParam(":url_ressource", $this->url_ressource);

            //execution de la requête
            if($query->execute()){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e) {
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
            //ecriture de la requête sql
            $sql = " UPDATE " . $this->table . "SET url_ressource = :url_ressource WHERE id_ressources = :id_ressources";
            //préparer la requête
            $query = $this->connexion->prepare($sql);
            //protection contre les injections
            $this->url1=htmlspecialchars(strip_tags($this->url_ressource));

            //ajout des données protégées
            $query->bindParam(":url_ressource", $this->url_ressource);
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
    $this->id_ressources = $id;
}
/**
 * Lecture des technologie (id)
 *
 * 
 */
public function lireId(){
    try{
        $sql = "SELECT id_ressources, url_ressource,  technologies_id_technologie FROM " . $this->table . " WHERE id_ressources = ? LIMIT 0,1";
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
//      echo"SQL Query: " . $sql;
        $query->bindParam(1, $this->id_ressources);
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
