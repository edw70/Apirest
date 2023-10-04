# Apirest

    <h1>Présentation de l'API</h1>
    <h2>API de Gestion de Ressources</h2>
    <p>Bienvenue sur l'API de gestion de ressources, une interface pour gérer des catégories, des technologies et des ressources. Cette API suit les conventions RESTful et offre un ensemble complet d'opérations pour vous permettre de créer, lire, mettre à jour et supprimer ces ressources.</p>

    <h2>Comment Utiliser l'API</h2>

    <p>L'API est accessible via des requêtes HTTP standard. Voici comment utiliser chaque opération :


     -  Lire Toutes une table (categories, technologies, ressources)
        URL : GET 
        Exemple : GET /ressources

     -  Lire une table par ID (categories, technologies, ressources)
        URL : GET /technologies/{id}
        Exemple : GET /technologies/2

        Créer une Ressource ou une categories
        
        URL : POST /ressources
        URL : POST /categories
        Données : JSON représentant la nouvelle ressource
        Exemple :
        json

        {
            "nom": "Nouvelle Ressource",
            "technologies_id_technologie": 1
        }


     -   Mettre à Jour une Ressource ou une categories
        
        URL : PUT /ressources/{id}
        Exemple : GET /ressources/2
        Données : JSON représentant la ressource mise à jour
        Exemple :
        json
        {
            "nom": "Ressource Mise à Jour",
            "technologies_id_technologie": 2
        }

        Supprimer un {id} d'une table (categories, technologies, ressources)
        
        URL : DELETE /ressources/{id}
        Exemple : DELETE /ressources/1

***********************
        Créer une une Technologie (tous les champs sont obligatoire)
                
        URL : POST /technologies
        Exemple : POST /technologies
                Données : form-data
                Exemple :







        
        Mettre à Jour une Technologie (id obligatoire)
        
        URL : PUT /technologies/{id}
        Données : form-data
        Exemple :
       
        
        <h2>Remarques importantes :</h2>
        <p>Pour supprimer une catégorie ou une technologie, assurez-vous que toutes les ressources associées ont été supprimées au préalable.
        
        



            

