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
    
            // Nettoyez et obtenez le nom de la technologie
            $nom = htmlspecialchars(strip_tags($data->nom));
            // Supprimez les espaces et convertissez le nom en minuscules
            $nom = str_replace(' ', '_', strtolower($nom));
            $categories_idcategories = htmlspecialchars(strip_tags($data->categories_idcategories));
            // Ajoutez "logo_" au début du nom
            $logo = "logo_" . $nom;
            // Récupérer l'extension du fichier téléchargé
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    
            // Vérifier si l'extension est valide (SVG, PNG, JPEG ou WEBP)
            if (!in_array($extension, ['svg', 'png', 'jpeg', 'webp'])) {
                return false; // Extension de fichier non valide
            }
    
            // Vérifier si le nom de la technologie existe déjà dans la base de données
            $sql_check = "SELECT id_technologie FROM " . $this->table . " WHERE nom = :nom";
            $query_check = $this->connexion->prepare($sql_check);
            $query_check->bindParam(":nom", $nom, PDO::PARAM_STR);
            $query_check->execute();
    
            if ($query_check->rowCount() > 0) {
                throw new Exception("technologie déjà existante");
            } else {
                // Enregistrez le fichier dans le répertoire souhaité
                $chemin_repertoire = './logos/';
                $chemin_complet_fichier = $chemin_repertoire . $logo . '.' . $extension;
    
                // Obtenez automatiquement le nom de domaine
                $domaine =  $_SERVER['HTTP_HOST']; // Cela obtient le nom de domaine du serveur
    
                // Construire l'adresse complète avec le nom de domaine
                $adresse_complete = $_SERVER['REQUEST_SCHEME']."://" . $domaine . $chemin_complet_fichier;
    
                // Écrire la requête SQL pour l'insertion
                $sql = "INSERT INTO " . $this->table . "(nom, logo, categories_idcategories) VALUES(:nom, :logo, :categories_idcategories)";
    
                // Préparer la requête
                $query = $this->connexion->prepare($sql);
    
                // Lié les champs à la requête
                $query->bindParam(":nom", $nom, PDO::PARAM_STR);
                $query->bindParam(":logo", $adresse_complete, PDO::PARAM_STR); // Stocker l'adresse complète
                $query->bindParam(":categories_idcategories", $categories_idcategories, PDO::PARAM_INT);
    
                // Exécution de la requête
                if ($query->execute()) {
                    // Enregistrez le fichier dans le répertoire des logos
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $chemin_complet_fichier)) {
                        return true; // Création réussie
                    } else {
                        return false; // Échec de l'enregistrement du fichier
                    }
                } else {
                    return false; // Échec de la création
                }
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            return false;
        }
    }

    /**
     * Mettre à jour une categorie
     *
     * *******************************************************************************
     */
    
    public function updateTechnologie($id_technologie, $nom, $categories_idcategories, $nomFichier, $logoDataBinary) {

        // Vérification de l'existence
        $query = "SELECT * FROM technologies WHERE id_technologie = :id AND `delete` = 0";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':id', $id_technologie);
        $stmt->execute();
    
        if ($stmt->rowCount() == 0) {
            throw new Exception("L'enregistrement n'existe pas, la mise à jour n'est pas possible");
        }
    
        // Récupération du logo actuel et du nom actuel
        $query = "SELECT logo, nom FROM technologies WHERE id_technologie = :id";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':id', $id_technologie);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $ancienLogoPath = $row['logo'];
        $ancienNom = $row['nom'];
    
        $updateFields = [];
    
        // Mise à jour du nom
        if (!empty($nom)) {
            $updateFields[] = "nom = :nom";
        } else {
            $nom = $ancienNom;  // Utilisez l'ancien nom si le nouveau nom n'est pas fourni
        }
    
        // Mise à jour de la catégorie
        if (!empty($categories_idcategories)) {
            $updateFields[] = "categories_idcategories = :categories_idcategories";
        }
    
        // Génération du nom de fichier pour le logo
        $ext = !empty($nomFichier) ? pathinfo($nomFichier, PATHINFO_EXTENSION) : pathinfo($ancienLogoPath, PATHINFO_EXTENSION);
        $logoFileName = "logo_" . $nom . "." . $ext;
        $updateFields[] = "logo = :logo";
        $chemin_repertoire = 'logos/';
        $chemin_complet_fichier = $chemin_repertoire . $logoFileName;
        // Obtenez automatiquement le nom de domaine
        $domaine =  $_SERVER['HTTP_HOST']; // Cela obtient le nom de domaine du serveur
        // Construire l'adresse complète avec le nom de domaine
        $adresse_complete = $_SERVER['REQUEST_SCHEME']."://" . $domaine ."/". $chemin_complet_fichier;
        // Préparez la requête de mise à jour avec les champs dynamiques

        $query = "UPDATE technologies SET " . implode(', ', $updateFields) . " WHERE id_technologie = :id";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':id', $id_technologie);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':categories_idcategories', $categories_idcategories);
        $stmt->bindParam(':logo', $adresse_complete);
    
        if ($stmt->execute()) { 
             // Gérer le logo
            if ($ancienLogoPath){
                $path = "logos/";
                $logoexplode = explode($path, $ancienLogoPath);
                $logoToDelete = $path.$logoexplode[1];
                if (!empty($nomFichier)) {
                    unlink($logoToDelete);
                    $temporaryFullPath = $path . $logoFileName;
                    file_put_contents($temporaryFullPath, $logoDataBinary);
                } else if ($nom !== $ancienNom) {  // Vérifier si le nom a été modifié
                    // Renommer le fichier du logo existant pour refléter le nouveau nom de la technologie
                        $newLogoPath = $path . $logoFileName;
                        rename($logoToDelete , $newLogoPath);
                }
            }
    
            return true; // Mise à jour réussie
        } else {
            return false; // Échec de la mise à jour
        }
    }
    
    
    /************************************************************************************
     * Lecture des technologie (id)
     *
     * 
     */
        public function lireId(){
            try{
                $sql = "SELECT t.id_technologie, t.nom, t.logo, t.categories_idcategories, c.nom AS nom_categorie
                        FROM " . $this->table . " t
                        INNER JOIN categories c ON t.categories_idcategories = c.idcategories
                        WHERE t.id_technologie = ? AND t.delete = 0 LIMIT 0,1";
                // On prépare la requête
                $query = $this->connexion->prepare($sql);
                $query->bindParam(1, $this->id_technologie);
                $query->execute();
        
                // On retourne le résultat
                return $query;
            } catch (PDOException $e){
                // Gestion des erreurs de base de données
                error_log("Erreur PDO dans la méthode lireIdAvecCategorie(): " . $e->getMessage());
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
        $sqlCheckResources = "SELECT COUNT(*) AS count FROM ressources WHERE technologies_id_technologie = :id_technologie";
        $queryCheckResources = $this->connexion->prepare($sqlCheckResources);
        $queryCheckResources->bindParam(":id_technologie", $id_technologie, PDO::PARAM_INT);
        $queryCheckResources->execute();
        $resourceCount = $queryCheckResources->fetch(PDO::FETCH_DEFAULT);

        if ($resourceCount["count"] > 0) {
            // Il y a des ressources associées, vérifiez si toutes les ressources sont marquées comme supprimées (delete = 0)
            return ["message" => "Impossible de supprimer la technologie, Certaines ressources sont actives."];
        } else {
            // Aucune ressource n'est attachée, procédez à la suppression de la technologie
            $sqlDelete = " UPDATE " . $this->table . " SET `delete` = 1 WHERE id_technologie = :id_technologie";
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