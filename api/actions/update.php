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

        $data = file_get_contents("php://input"); // Obtenez les données de la requête PUT

        try {
            // Créez une instance du modèle approprié (basé sur le nom de la table)
            $model = new $table($db);
            $model->setId($id); // Définissez l'ID de la ressource

            // Analysez les données reçues (multipart/form-data) de la requête PUT
            $explode = explode("Content-Disposition: form-data; name=", $data);
            
            // Parcourez les données et extrayez les champs pertinents
            for($i = 0; $i < sizeof($explode); $i++) {
                $explodeExplode[] = explode("----------------------------", $explode[$i]);
            }

            for($i = 1; $i < sizeof($explodeExplode); $i++) {
                if (str_contains($explodeExplode[$i][0], '"id_technologie"') 
                    || str_contains($explodeExplode[$i][0], '"nom"')
                    || str_contains($explodeExplode[$i][0], '"categories_idcategories"')) {
                    $explodeExplodeExplode[] = explode("\n", $explodeExplode[$i][0]);
                } else if (str_contains($explodeExplode[$i][0], '"logo"')) {
                    $logoExplode[] = explode('Content-Type: image/png', $explodeExplode[$i][0]);
                }
            }

            // Parcourez les données extraites et créez un tableau associatif de résultats
            for($i = 0; $i < sizeof($explodeExplodeExplode); $i++) {
                if (str_contains($explodeExplodeExplode[$i][0], '"id_technologie"')) {
                    $name = explode('"', $explodeExplodeExplode[$i][0]);
                    $length = strlen($explodeExplodeExplode[$i][2]);
                    $tmp = substr($explodeExplodeExplode[$i][2],0,$length-1);
                    $result[] = ["name" => $name[1], "id_technologie" => $tmp];
                    
                } else if (str_contains($explodeExplodeExplode[$i][0], '"nom"')) {
                    $name = explode('"', $explodeExplodeExplode[$i][0]);
                    $length = strlen($explodeExplodeExplode[$i][2]);
                    $tmp = substr($explodeExplodeExplode[$i][2],0,$length-1);
                    $result[] = ["name" => $name[1], "nom" => $tmp];
                } else if (str_contains($explodeExplodeExplode[$i][0], '"categories_idcategories"')) {
                    $name = explode('"', $explodeExplodeExplode[$i][0]);
                    $length = strlen($explodeExplodeExplode[$i][2]);
                    $tmp = substr($explodeExplodeExplode[$i][2],0,$length-1);
                    $result[] = ["name" => $name[1], "categories_idcategories" => $tmp];
                }
            }

            // Traitez les données du logo
            $pattern = "/\r\n/";
            $logoDataBinary = preg_replace($pattern, '',$logoExplode[0][1], 2);
            $logoExplodeExplode = explode('"logo"; filename=', $logoExplode[0][0]);
            $fileName = explode('"', $logoExplodeExplode[1]);
            $result[] = ["fileName" => $fileName[1], "dataFile" => $logoDataBinary];
            //$logoDataBinary = image crypté

            //attribut une variable pour stocker chaque donnée et les envoyer dans la fonction
            $id_technologie = $result[0]['id_technologie'];//(valeur)
            $nom = $result[1]['nom'];//(nom de la technologie)
            $nomFichier = $fileName[1];
            $categories_idcategories = $result[2]['categories_idcategories'];//(valeur)
            
    //        $delete = 0; // Vous devez déterminer la valeur de $delete en fonction de votre logique 

            if ($table === 'technologies' && $model->updateTechnologie($id_technologie, $nom,$categories_idcategories, $nomFichier, $logoDataBinary)) {
                http_response_code(201); // Created
                echo json_encode(["message" => "La ressource a été mise à jour avec succès"]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["message" => "Erreur lors de la mise à jour de la ressource"]);
            }
        } catch (Exception $e) {
            // Gestion des erreurs générales
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Une erreur est survenue lors du traitement de la demande"]);
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
                        }
                    } else {
                        http_response_code(400); // Mauvaise requête
                        echo json_encode(["message" => "La méthode 'update' n'est pas prise en charge pour cette ressource"]);
                    }
                } else {
                    http_response_code(500); // Erreur interne du serveur
                    echo json_encode(["message" => "Échec de l'enregistrement du fichier"]);
                }
            } else {
                http_response_code(400); // Mauvaise requête
                echo json_encode(["message" => "Le fichier logo n'est pas une image valide"]);
            }
        } catch (Exception $e) {
            // Gestion des erreurs générales
            http_response_code(500); // Erreur interne du serveur
            echo json_encode(["message" => "Une erreur est survenue lors du traitement de la demande:" . $e->getMessage()]);
        }
    } else {
        // Mauvaise URL, on gère l'erreur
        http_response_code(400); // Mauvaise requête
        echo json_encode(["message" => "URL non valide"]);
    }
} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}*/

 






