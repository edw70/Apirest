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
    public function lire() {
        try {
            // On écrit la requête SQL en ajoutant une clause WHERE pour sélectionner les catégories avec `delete` égal à 0
            $sql = "SELECT idcategories, nom FROM " . $this->table . " WHERE `delete` = 0";
            // On prépare la requête
            $query = $this->connexion->prepare($sql);
            $query->execute();
            // On retourne le résultat
            return $query;
        } catch (PDOException $e) {
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
            // Vérifier si le champ 'nom' existe dans les données
            if (!isset($data->nom)) {
                return false; // Le champ 'nom' est manquant, la création n'est pas possible
            }
    
            // Échapper et nettoyer le champ 'nom'
            $nom = htmlspecialchars(strip_tags($data->nom));
    
            //  requête SQL pour l'insertion
            $sql = "INSERT INTO " . $this->table . "(nom) VALUES(:nom)";
    
            // Préparer la requête
            $query = $this->connexion->prepare($sql);
    
            // Lié le champ 'nom' à la requête
            $query->bindParam(":nom", $nom, PDO::PARAM_STR);
    
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
        // Assurez-vous que l'ID de la catégorie est défini
        if (!isset($this->idcategories)) {
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
        $sql .= " WHERE idcategories = :idcategories AND `delete` = 0";
        $params[':idcategories'] = $this->idcategories;

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
    $this->idcategories = $id;
}
/**
 * Lecture des categories (id)
 *
 * 
 */
public function lireId(){
    try{
        $sql = "SELECT idcategories, nom FROM " . $this->table . " WHERE idcategories = ? AND `delete` = 0 LIMIT 0,1";
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        $query->bindParam(1, $this->idcategories);
        $query->execute();

        // On retourne le résultat
        return $query;
    } catch (PDOException $e){
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
public function delete($idcategories) {
    // Vérifiez si des technologies sont liées à cette catégorie
    $sqlCheckTechnologies = "SELECT COUNT(*) FROM technologies WHERE categories_idcategories = :idcategories";
    $queryCheckTechnologies = $this->connexion->prepare($sqlCheckTechnologies);
    $queryCheckTechnologies->bindParam(":idcategories", $idcategories, PDO::PARAM_INT);
    $queryCheckTechnologies->execute();
    $technologyCount = $queryCheckTechnologies->fetchColumn();

    if ($technologyCount > 0) {
        // Des technologies sont liées, renvoyez un message d'erreur
        return ["message" => "Supprimez ou modifier d'abord les technologies associées à cette catégorie."];
    } else {
        // Aucune technologie n'est liée, procédez à la suppression de la catégorie
        $sqlDelete = "UPDATE " . $this->table . " SET `delete` = 1 WHERE idcategories = :idcategories";
        $queryDelete = $this->connexion->prepare($sqlDelete);
        $queryDelete->bindParam(":idcategories", $idcategories, PDO::PARAM_INT);

        if ($queryDelete->execute()) {
            return ["message" => "La catégorie a été supprimée avec succès."];
        } else {
            return ["message" => "Une erreur est survenue lors de la suppression de la catégorie."];
        }
    }
}

}