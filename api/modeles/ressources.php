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
            $sql = "SELECT id_ressources, url_ressource, technologies_id_technologie FROM " . $this->table . " WHERE `delete` = 0";
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

     public function create($data) {
        try {
            // Vérifier si les champs requis existent dans les données
            if (!isset($data->url_ressource) || !isset($data->technologies_id_technologie)) {
                return false; // Les champs requis sont manquants, la création n'est pas possible
            }

            // Échapper et nettoyer les champs
            $url_ressource = htmlspecialchars(strip_tags($data->url_ressource));
            $technologies_id_technologie = htmlspecialchars(strip_tags($data->technologies_id_technologie));

            // Écrire la requête SQL pour l'insertion
            $sql = "INSERT INTO " . $this->table . "(url_ressource, technologies_id_technologie) VALUES(:url_ressource, :technologies_id_technologie)";

            // Préparer la requête
            $query = $this->connexion->prepare($sql);

            // Lié les champs à la requête
            $query->bindParam(":url_ressource", $url_ressource, PDO::PARAM_STR);
            $query->bindParam(":technologies_id_technologie", $technologies_id_technologie, PDO::PARAM_INT);

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
        if (!isset($this->id_ressources)) {
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
        $sql .= " WHERE id_ressources = :id_ressources";
        $params[':id_ressources'] = $this->id_ressources;
    
        // Exécuter la requête avec les paramètres
        $query = $this->connexion->prepare($sql);
        if ($query->execute($params)) {
            return true;
        } else {
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
        $sql = "SELECT id_ressources, url_ressource,  technologies_id_technologie FROM " . $this->table . " WHERE id_ressources = ? AND `delete` = 0 LIMIT 0,1";
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
    /**
 * Delete par suppression logique
 *
 * 
 */
public function delete($id) { 
try {
    
    // Requête SQL pour effectuer la suppression logique (mettre delete à 1)
    $sql = "UPDATE " . $this->table . " SET `delete` = 1 WHERE id_ressources = :id_ressources";
    

        // Préparez la requête
        $query = $this->connexion->prepare($sql);
        
        // Lier l'ID de la ressource à supprimer
        $query->bindParam(":id_ressources", $id, PDO::PARAM_INT);

        // Exécutez la requête
        if ($query->execute()) {
    
            return true; // La ressource a été supprimée avec succès (marquée comme supprimée)
        } else {
            return false; // Une erreur est survenue lors de la suppression de la ressource
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de base de données

        return false;
    }
}


}