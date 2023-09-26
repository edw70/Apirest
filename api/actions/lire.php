<?php

//headers requis
//acces depuis n'importe quel site ou appareil (*)
//header("Access-Control-Allow-Origin: *");

//Format des données envoyées
//header("Content-Type: application/json; charset=UTF8");

//methode autorisée
//header("Access-Control-Allow-Methods: GET");

//durée de vie de la requête
//header("Access-Control-Max-Age: 3600"); //1h c'est le cache

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtenir l'URL demandée par l'utilisateur
    $uri = $_SERVER['REQUEST_URI'];

    // Supprimer la chaîne de requête (query string) de l'URI
    if(false !==$pos = strpos($uri, '?')){
        $uri = substr($uri, 0, $pos);
    }

    // Diviser l'URI en segments
    $segments = explode('/', trim($uri, '/'));
    // Le premier segment devrait contenir le nom de la table
    if(!empty($segments) && isset($segments[0])){
        $table = $segments[0];
        // Inclure les fichiers et instancier la base de données
        include_once './database/connexion_db.php';
        include_once './modeles/categories.php';
        include_once './modeles/technologies.php';
        include_once './modeles/ressources.php';

        $database = new database();
        $db = $database->getConnection();
    

        try{
            // Créez une instance du modèle approprié
            $model = new $table($db);
            $stmt = $model->lire();

            if ($stmt->rowCount() > 0) {
                // Initialiser le tableau de réponse
                $tableauReponse = [];
                $tableauReponse[$table] = [];

                // Parcourir les résultats et ajouter au tableau de réponse
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tableauReponse[$table][] = $row;
                }

                // Répondre avec le tableau de réponse JSON
                http_response_code(200);
                echo json_encode($tableauReponse);
            } else {
                // Répondre avec un message si la table est vide
                http_response_code(404); // Not Found
                echo json_encode(["message" => "Aucune donnée trouvée dans la table $table"]);
            }
        } catch (Exception $e) {
            // Gestion des erreurs générales
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Une erreur est survenue lors du traitement de la demande"]);
        }
    } else {
        // Paramètre 'table' manquant dans la requête GET
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Paramètre 'table' manquant dans la requête GET"]);
    }
} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}




