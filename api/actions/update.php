<?php
// Inclure les fichiers et instancier la base de données
include_once './database/connexion_db.php'; // Inclure le fichier de connexion à la base de données
include_once './modeles/categories.php'; // Inclure le modèle de catégories
include_once './modeles/technologies.php'; // Inclure le modèle de technologies
include_once './modeles/ressources.php'; // Inclure le modèle de ressources

// Vérifier si la méthode de la requête est PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    // Obtenir l'URL demandée par l'utilisateur
    $uri = $_SERVER['REQUEST_URI'];

    // Supprimer la chaîne de requête (query string) de l'URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    // Diviser l'URI en segments
    $segments = explode('/', trim($uri, '/'));

    // Assurez-vous qu'il y a au moins deux segments
    if (count($segments) >= 2) {
        $table = $segments[0]; // Le premier segment est le nom de la table dans la base de données
        $id = $segments[1]; // Le deuxième segment est l'ID de la ressource à mettre à jour

        $database = new database(); // Créez une instance de la classe de connexion à la base de données
        $db = $database->getConnection(); // Obtenez la connexion à la base de données

       
        $data = json_decode(file_get_contents("php://input"));
        try {
            // Créez une instance du modèle approprié (basé sur le nom de la table)
            $model = new $table($db);
            $model->setId($id);

            if($table === 'technologies'){
                $data = file_get_contents("php://input"); // Obtenez les données de la requête PUT
                $id_technologie = $model->setId($id); // Définissez l'ID de la ressource
                $nom = "";
                $categories_idcategories = "";
                $nomFichier = "";
                $logoDataBinary = "";
                // Analysez les données reçues (multipart/form-data) de la requête PUT
                $explode = explode("Content-Disposition: form-data; name=", $data);
                // Parcourez les données et extrayez les champs pertinents
                for($i = 0; $i < sizeof($explode); $i++) {
                    $explode2[] = explode("----------------------------", $explode[$i]);
                }

                $logoExist = false ;
                for($i = 1; $i < sizeof($explode2); $i++) {
                    if (str_contains($explode2[$i][0], '"id_technologie"') 
                        || str_contains($explode2[$i][0], '"nom"')
                        || str_contains($explode2[$i][0], '"categories_idcategories"')) {
                        $explode3[] = explode("\n", $explode2[$i][0]);
                    } else if (str_contains($explode2[$i][0], '"logo"')) {
                        $logoExplode[] = explode('Content-Type: image/', $explode2[$i][0]);
                        $logoExist = true;
                    }
                }

                // Parcourez les données extraites et créez un tableau associatif de résultats
                for($i = 0; $i < sizeof($explode3); $i++) {
                    if (str_contains($explode3[$i][0], '"id_technologie"')) {
                        $name = explode('"', $explode3[$i][0]);
                        $length = strlen($explode3[$i][2]);
                        $tmp = substr($explode3[$i][2],0,$length-1);
                        $id_technologie = $tmp;
                        // $result[] = ["name" => $name[1], "id_technologie" => $tmp];
                    } else if (str_contains($explode3[$i][0], '"nom"')) {
                        $name = explode('"', $explode3[$i][0]);
                        $length = strlen($explode3[$i][2]);
                        $tmp = substr($explode3[$i][2],0,$length-1);
                        $nom = $tmp;
                        // $result[] = ["name" => $name[1], "nom" => $tmp];
                    } else if (str_contains($explode3[$i][0], '"categories_idcategories"')) {
                        $name = explode('"', $explode3[$i][0]);
                        $length = strlen($explode3[$i][2]);
                        $tmp = substr($explode3[$i][2],0,$length-1);
                        // $result[] = ["name" => $name[1], "categories_idcategories" => $tmp];
                        $categories_idcategories = $tmp;
                    }
                }

                // Traitez les données du logo
                if($logoExist){
                    $logoExplodeExplode = explode('"logo"; filename=', $logoExplode[0][0]);
                    $fileName = explode('"', $logoExplodeExplode[1]);
                    $nomFichier = $fileName[1];
                    $pattern = "/([a-zA-Z0-9\+]+)(\r\n){2,2}/";
                    $logoDataBinary = preg_replace($pattern, '',$logoExplode[0][1], 1);
                    //$logoDataBinary = image crypté
                }
            }
            if ($table === 'technologies' && $model->updateTechnologie($id_technologie, $nom,$categories_idcategories, $nomFichier, $logoDataBinary)) {
                http_response_code(200); // OK
                echo json_encode(["message" => "La ressource a été mise à jour avec succès"]);
            
            } elseif (method_exists($model, 'update')) {
                // Si le modèle a une méthode 'update', utilisez-la pour mettre à jour la ressource
                if ($model->update($data)) {
                    
                    http_response_code(200); // OK
                    echo json_encode(["message" => "La ressource a été modifiée avec succès"]);
                } else {
                    http_response_code(500); // Erreur interne du serveur
                    echo json_encode(["message" => "Une erreur est survenue lors de la modification de la ressource"]);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(["message" => "Méthode de mise à jour non prise en charge pour cette ressource"]);
            }
        } catch (Exception $e) {
            // Gestion des erreurs générales
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => $e->getMessage()]);
        }
    } else {
        // Mauvaise URL, on gère l'erreur
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "URL non valide"]);
    }
} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}







    
    
          /*           // Vérifiez si le modèle approprié a une méthode 'update'
                    if (method_exists($model, 'update')) {    //ne pas enlever
                        if ($model->update($data)) {
                            http_response_code(200); // OK
                            echo json_encode(["message" => "La ressource a été modifiée avec succès"]);
                        } else {
                            http_response_code(500); // Erreur interne du serveur
                            echo json_encode(["message" => "Une erreur est survenue lors de la modification de la technologie"]);
                        }*/
                








