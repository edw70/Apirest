<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Api Rest</title>
</head>
<body>
    <h1>Présentation de l'API</h1>
    <h2>API de Gestion de Ressources</h2>
    <p>Bienvenue sur l'API de gestion de ressources, une interface simple et puissante pour gérer des catégories, des technologies et des ressources. Cette API suit les conventions RESTful et offre un ensemble complet d'opérations pour vous permettre de créer, lire, mettre à jour et supprimer ces ressources.</p>

    <h2>Comment Utiliser l'API</h2>
    <p>L'API est accessible via des requêtes HTTP standard. Voici comment utiliser chaque opération :

        Créer une Ressource
        
        URL : POST /ressources
        Données : JSON représentant la nouvelle ressource
        Exemple :
        json

        {
            "nom": "Nouvelle Ressource",
            "technologies_id_technologie": 1
        }
        Lire Toutes les Catégories, Technologies ou Ressources
        
        URL : GET /categories, GET /technologies, GET /ressources
        Exemple : GET /categories
        Lire une Catégorie, une Technologie ou une Ressource par ID
        
        URL : GET /categories/{id}, GET /technologies/{id}, GET /ressources/{id}
        Exemple : GET /technologies/1
        Mettre à Jour une Ressource
        
        URL : PUT /ressources/{id}
        Données : JSON représentant la ressource mise à jour
        Exemple :
        json

        {
            "nom": "Ressource Mise à Jour",
            "technologies_id_technologie": 2
        }
        Supprimer une Ressource
        
        URL : DELETE /ressources/{id}
        Exemple : DELETE /ressources/1
        Mettre à Jour une Catégorie ou une Technologie
        
        URL : PUT /categories/{id}, PUT /technologies/{id}
        Données : JSON représentant la catégorie ou la technologie mise à jour
        Exemple :
        json

        {
            "nom": "Catégorie Mise à Jour"
        }
        Supprimer une Catégorie ou une Technologie
        
        URL : DELETE /categories/{id}, DELETE /technologies/{id}
        Exemple : DELETE /categories/1</p>
        <h2>Remarques importantes :</h2>
        <p>Pour supprimer une catégorie ou une technologie, assurez-vous que toutes les ressources associées ont été supprimées au préalable.
        Pour les opérations de mise à jour et de suppression, vous devez fournir l'ID de la ressource à modifier ou supprimer dans l'URL.</p>

        <p>Exemple de Requête :

            http
        
            POST /ressources
            Content-Type: application/json
            
            {
                "nom": "Nouvelle Ressource",
                "technologies_id_technologie": 1
            }
            Réponse :
            
            json

            {
                "message": "La ressource a été créée avec succès"
            }



            technologie put id obligatoire dans le champs fom-data
            </p>
</body>
</html>