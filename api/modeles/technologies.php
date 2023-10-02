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
            if (!in_array($extension, ['svg', 'png', 'jpeg', 'webp'])){
                return false; //extension de fichier non valide
            }
            // Enregistrez le fichier dans le répertoire souhaité
            $chemin_repertoire = './logos/';
            $chemin_complet_fichier = $chemin_repertoire . $logo . '.' . $extension;
            

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
                
                // Enregistrez le fichier dans le répertoire des logos
                if(move_uploaded_file($_FILES['logo']['tmp_name'], $chemin_complet_fichier)){
                
                    return true; // Création réussie
                } else {

                    return false; // Échec de l'enregistrement du fichier
                }
            }else {
            
                return false; //Échec de la création
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
    
     public function updateTechnologie($id_technologie, $nom, $categories_idcategories, $nomFichier, $logoDataBinary) {
        // var_dump($logoDataBinary);
        // Vérifiez d'abord si l'enregistrement existe
        $query = "SELECT * FROM technologies WHERE id_technologie = :id AND `delete` = 0";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':id', $id_technologie);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return false; // L'enregistrement n'existe pas, la mise à jour n'est pas possible
            echo json_encode(["message" => "L'enregistrement n'existe pas, la mise à jour n'est pas possible"]);
        }

        // Mettez à jour les données de la technologie
        $query = "UPDATE technologies SET nom = :nom, logo = :logo, categories_idcategories = :categories_idcategories WHERE id_technologie = :id";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':id', $id_technologie);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':logo', $nomFichier); // Le nom du fichier est utilisé comme logo
        $stmt->bindParam(':categories_idcategories', $categories_idcategories);

        if ($stmt->execute()) {
            // Enregistrez le logo dans le fichier logo_(nom de la technologie).(extension du fichier chargé)
            $path = "logos/";
            var_dump($nom);
            $logoFileName = "logo_" . $nom;
            $temporaryFullPath = $path.$logoFileName;
             //. "." . pathinfo($nomFichier, PATHINFO_EXTENSION);
            file_put_contents($temporaryFullPath, $logoDataBinary);
            $ext = mime_content_type($temporaryFullPath);
            $recupExt = explode("/", $ext);
            $fileFullPath = $temporaryFullPath.".".$recupExt[1];
            rename ($temporaryFullPath, $fileFullPath);
            // $result[] = ["extension"=>$recupExt[1]];
        //    var_dump($recupExt);
            return true; // Mise à jour réussie
        } else {
            return false; // Échec de la mise à jour
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