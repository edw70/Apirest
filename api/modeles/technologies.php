<?php
class technologies{
    //Connexion
    private $connexion;
    private $table = "technologies"; //table dans la base de données

    //Propriétés
    public $id_technologie;
    public $nom;
    public $logo;
    public $categories_idcategories;


     /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }
    // Fonction pour définir l'ID
    public function setId($id)
    {
        $this->id_technologie = $id;
    }
 /**
     * Lecture des categories (toutes)
     *
     * 
     */
    public function lire(){
        try{
            //on écrit la requête sql *$this table permet d'aller dans la table definis au debut du code
            $sql = "SELECT id_technologie, nom, logo, categories_idcategories  FROM " . $this->table;
            //on prepare la requête
            $query = $this->connexion->prepare($sql);
            $query->execute();

            //on retourne le resultat
            return $query;
        }catch (PDOException $e){
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
            $sql = " INSERT INTO " . $this->table . " SET nom=:nom, logo=:logo, categories_idcategories=:categories_idcategories ";
            //préparer la requête
            $query = $this->connexion->prepare($sql);

            //protection contre les injections
            $this->nom=htmlspecialchars(strip_tags($this->nom));
            $this->logo=htmlspecialchars(strip_tags($this->logo));
            $this->categories_idcategories=htmlspecialchars(strip_tags($this->categories_idcategories));

            //ajout des données protégées
            $query->bindParam(":nom", $this->nom,PDO::PARAM_STR);
            $query->bindParam(":logo", $this->logo, PDO::PARAM_STR);
            $query->bindParam(":categories_idcategories", $this->categories_idcategories, PDO::PARAM_INT);
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
            //ecriture de la requête sql
            $sql = " UPDATE " . $this->table . " SET nom=:nom, logo=:logo, categories_idcategories=:categories_idcategories WHERE id_technologie = :id_technologie";
            //préparer la requête
            $query = $this->connexion->prepare($sql);
          // Protection contre les injections
            
        if ($this->nom !== null) {
            $this->nom = htmlspecialchars(strip_tags($this->nom));
        }

        if ($this->logo !== null) {
            $this->logo = htmlspecialchars(strip_tags($this->logo));
        }

        if ($this->categories_idcategories !== null) {
            $this->categories_idcategories = htmlspecialchars(strip_tags($this->categories_idcategories));
        }
        
        $query->bindParam(":id_technologie", $this->id, PDO::PARAM_INT);
        $query->bindParam(":nom", $this->nom, PDO::PARAM_STR);
        $query->bindParam(":logo", $this->logo, PDO::PARAM_STR);
        $query->bindParam(":categories_idcategories", $this->categories_idcategories, PDO::PARAM_INT);
        if($query->execute()){
            
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
             // Gestion des erreurs de base de données
            return false;
        }
    }
    
    /**
     * Lecture des technologie (id)
     *
     * 
     */
    public function lireId(){
        try{
            $sql = "SELECT id_technologie, nom, logo, categories_idcategories FROM " . $this->table . " WHERE id_technologie = ? LIMIT 0,1";
            // On prépare la requête
            $query = $this->connexion->prepare($sql);
    //      echo"SQL Query: " . $sql;
            $query->bindParam(1, $this->id_technologie);
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