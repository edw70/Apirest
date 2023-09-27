<?php
// Inclure les fichiers et instancier la base de données
include_once './database/connexion_db.php';
include_once './modeles/categories.php';
include_once './modeles/technologies.php';
include_once './modeles/ressources.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtenir l'URL demandée par l'utilisateur
    $uri = $_SERVER['REQUEST_URI'];

    // Supprimer la chaîne de requête (query string) de l'URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    // Diviser l'URI en segments
    $segments = explode('/', trim($uri, '/'));

    // Assurez-vous qu'il y a au moins deux segments
    if (count($segments) >= 1) {
        $table = $segments[0];

        $database = new database();
        $db = $database->getConnection();

        try {
            // Créez une instance du modèle approprié
            $model = new $table($db);

            if (method_exists($model, 'create')) {
                // Lire les données de la requête POST depuis php://input
                $data = json_decode(file_get_contents("php://input"));
                // Vérifiez si les données sont valides 
            //    var_dump($data);
                if ($data && $model->create($data)) { // Utilisez la fonction create
                    http_response_code(201); // Created
                    echo json_encode(["message" => "La ressource a été créée avec succès"]);
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
