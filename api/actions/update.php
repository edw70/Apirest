<?php
// Inclure les fichiers et instancier la base de données
require_once './database/connexion_db.php';
require_once './modeles/technologies.php';
require_once './modeles/ressources.php';
require_once './modeles/categories.php';


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
        $table = $segments[0];
        $id = $segments[1];

        $database = new database();
        $db = $database->getConnection();

        try {
            // Créez une instance du modèle approprié
            $model = new $table($db);
            $model->setId($id);

            if (method_exists($model, 'update')) {
                  // Lire les données de la requête PUT depuis php://input
                $data = json_decode(file_get_contents("php://input"));
                // Vérifiez si les données sont valides 
                if ($data && $model->update($data)) {
                    http_response_code(200); // ok
                    echo json_encode(["message" => "La ressource a été modifiée avec succès"]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["message" => "Une erreur est survenue lors de la création de la ressource"]);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(["message" => "Action non valide"]);
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


