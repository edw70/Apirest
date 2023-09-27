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
            $sql = "SELECT id_technologie, nom, logo, categories_idcategories  FROM " . $this->table . " WHERE `delete` = 0";
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

    public function create($data) {
        try {
            // Vérifier si les champs requis existent dans les données
            if (!isset($data->nom) || !isset($data->logo) || !isset($data->categories_idcategories)) {
                return false; // Les champs requis sont manquants, la création n'est pas possible
            }

            // Échapper et nettoyer les champs
            $nom = htmlspecialchars(strip_tags($data->nom));
            $logo = htmlspecialchars(strip_tags($data->logo));
            $categories_idcategories = htmlspecialchars(strip_tags($data->categories_idcategories));

            // Écrire la requête SQL pour l'insertion
            $sql = "INSERT INTO " . $this->table . "(nom, logo, categories_idcategories) VALUES(:nom, :logo, :categories_idcategories)";

            // Préparer la requête
            $query = $this->connexion->prepare($sql);

            // Lié les champs à la requête
            $query->bindParam(":nom", $nom, PDO::PARAM_STR);
            $query->bindParam(":logo", $logo, PDO::PARAM_STR);
            $query->bindParam(":categories_idcategories", $categories_idcategories, PDO::PARAM_INT);

            // Exécution de la requête
            if ($query->execute()) {
                return true; // Création réussie
            } else {
                return false; // Échec de la création
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            return false;
        }
    }
    /**
     * Mettre à jour une categorie
     *
     * 
     */
    public function update($data) {
        // Assurez-vous que l'ID de la technologie est défini
        if (!isset($this->id_technologie)) {
            return false; // ID manquant, la mise à jour est impossible
        }
    
        // Vérifiez si les données à mettre à jour sont fournies
        if (empty($data)) {
            return false; // Pas de données fournies, pas de mise à jour nécessaire
        }
    
        // Construire la requête SQL pour la mise à jour
        $sql = "UPDATE " . $this->table . " SET ";
    
        $params = array();
        foreach ($data as $key => $value) {
            // Échapper et ajouter chaque champ à la requête SQL
            $key = htmlspecialchars(strip_tags($key));
            $sql .= $key . " = :" . $key . ", ";
            $params[":" . $key] = $value;
        }
    
        // Supprimer la virgule finale de la requête SQL
        $sql = rtrim($sql, ', ');
    
        // Ajouter la clause WHERE pour identifier la ligne à mettre à jour
        $sql .= " WHERE id_technologie = :id_technologie";
        $params[':id_technologie'] = $this->id_technologie;
    
        // Exécuter la requête avec les paramètres
        $query = $this->connexion->prepare($sql);
        if ($query->execute($params)) {
            return true;
        } else {
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
            $sql = "SELECT id_technologie, nom, logo, categories_idcategories FROM " . $this->table . " WHERE id_technologie = ? AND `delete` = 0 LIMIT 0,1";
            // On prépare la requête
            $query = $this->connexion->prepare($sql);
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

    /**
     * Delete par suppression logique
     *
     * 
     */
    public function delete($id_technologie) {
        // Vérifiez si des ressources sont attachées à cette technologie
        $sqlCheckResources = "SELECT COUNT(*) FROM ressources WHERE technologies_id_technologie = :id_technologie";
        $queryCheckResources = $this->connexion->prepare($sqlCheckResources);
        $queryCheckResources->bindParam(":id_technologie", $id_technologie, PDO::PARAM_INT);
        $queryCheckResources->execute();
        $resourceCount = $queryCheckResources->fetchColumn();

        if ($resourceCount > 0) {
            // Des ressources sont attachées, renvoyez un message d'erreur
            return ["message" => "Supprimez d'abord les ressources associées à cette technologie."];
        } else {
            // Aucune ressource n'est attachée, procédez à la suppression de la technologie
            $sqlDelete = "UPDATE " . $this->table . "SET `delete`= 1 WHERE id_technologie = :id_technologie";
            $queryDelete = $this->connexion->prepare($sqlDelete);
            $queryDelete->bindParam(":id_technologie", $id_technologie, PDO::PARAM_INT);

            if ($queryDelete->execute()) {
                return ["message" => "La technologie a été supprimée avec succès."];
            } else {
                return ["message" => "Une erreur est survenue lors de la suppression de la technologie."];
            }
        }
    }
}