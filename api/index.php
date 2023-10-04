<?php
// Fonction pour afficher les en-têtes communs
function setCommonHeaders(){
    //headers requis
//acces depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

//Format des données envoyées
header("Content-Type: application/json; charset=UTF8");
// Spécifier le type de contenu multipart/form-data

//methode autorisée
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

//durée de vie de la requête
header("Access-Control-Max-Age: 3600"); //1h c'est le cache
}

// Fonction pour afficher la liste de toutes les routes
function accueil(){
    setCommonHeaders();
    include './explicatif.php';     //a modifier 
    
}

// Fonction pour lire toute la table technologies (GET)
function getTables() {
    setCommonHeaders();
    include './actions/lire.php';  
}

// Fonction pour lire un id la table technologies (GET)
function getId() {
    setCommonHeaders();
    include './actions/lire_id.php';  
}

// Fonction pour créer une nouvelle technologie (POST)
function createT() {
    setCommonHeaders();
    include './actions/create.php';
}

// Fonction pour mettre à jour une technologie (PUT)
function updateT($id) {
    setCommonHeaders();
    include './actions/update.php';
}

// Fonction pour supprimer une technologie (DELETE)
function deleteId($id) {
    setCommonHeaders();
    include './actions/delete.php';
}

// Tableau de configuration des routes
$routes = [
    'GET:/' => 'accueil',
    'GET:/technologies' => 'getTables',
    'GET:/technologies/{id}' => 'getId',
    'POST:/technologies' => 'createT',
    'PUT:/technologies/{id}' => 'updateT',
    'DELETE:/technologies/{id}' => 'deleteId',

    'GET:/ressources' => 'getTables',
    'GET:/ressources/{id}' => 'getId',
    'POST:/ressources' => 'createT',
    'PUT:/ressources/{id}' => 'updateT',
    'DELETE:/ressources/{id}' => 'deleteId',

    'GET:/categories' => 'getTables',
    'GET:/categories/{id}' => 'getId',
    'POST:/categories' => 'createT',
    'PUT:/categories/{id}' => 'updateT',
    'DELETE:/categories/{id}' => 'deleteId',
    
];

// Fonction de routage
function router($url, $method) {
    global $routes;// Accès aux routes définies

    foreach ($routes as $pattern => $handler) { // Parcourir toutes les routes
        $fullUrl = $method . ':' . $url; // Créer l'URL complète en combinant la méthode HTTP et l'URL demandée
          // Remplacer {id} par (\d+) dans le modèle de route
        $pattern = str_replace('{id}', '(\d+)', $pattern);
        // Échapper les caractères '/' dans le modèle de route
        $pattern = str_replace('/', '\/', $pattern);

        // Vérifier si l'URL demandée correspond au modèle de route
        if (preg_match('/^' . $pattern . '$/', $fullUrl, $matches)) {
            array_shift($matches); // Supprimer l'URL complète, nous ne voulons que les valeurs variables
            // Appeler la fonction associée à la route avec les données correspondantes
            call_user_func($handler, $matches);
            return;// Terminer le traitement après avoir trouvé la correspondance
        }
    }
    // Si aucune correspondance n'est trouvée, retourner une erreur 404
    header('HTTP/1.0 404 Not Found');
}

// Récupérer l'URL demandée par l'utilisateur et la méthode HTTP
$request_url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Appeler le routeur avec l'URL et la méthode
router($request_url, $method);