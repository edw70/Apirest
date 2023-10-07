# Apirest

    ## Présentation de l'API

## API de Gestion de Ressources

Bienvenue sur l'API de gestion de ressources, une interface pour gérer des catégories, des technologies et des ressources. Cette API suit les conventions RESTful et offre un ensemble complet d'opérations pour vous permettre de créer, lire, mettre à jour et supprimer ces ressources.

## Comment Utiliser l'API

L'API est accessible via des requêtes HTTP standard. Voici comment utiliser chaque opération :

### Lire Toutes une table (categories, technologies, ressources)

- URL : `GET /ressources`
- Exemple : `GET /ressources`

### Lire une table par ID (categories, technologies, ressources)

- URL : `GET /technologies/{id}`
- Exemple : `GET /technologies/2`

### Créer une Ressource ou une catégorie

- URL : `POST /ressources` ou `POST /categories`
- Données : JSON représentant la nouvelle ressource
- Exemple :
- 
  ```json
  {
      "nom": "Nouvelle Ressource",
      "technologies_id_technologie": 1
  }
  ```
#### Mettre à Jour une Ressource ou une catégorie
URL : PUT /ressources/{id} ou PUT /categories/{id}
Exemple : PUT /ressources/2
Données : JSON représentant la ressource mise à jour

Exemple :
```
{
    "nom": "Ressource Mise à Jour",
    "technologies_id_technologie": 2
}
```
#### Supprimer un {id} d'une table (categories, technologies, ressources)
URL : DELETE /ressources/{id}
Exemple : DELETE /ressources/1

#### Créer une Technologie (tous les champs sont obligatoires)
URL : POST /technologies
Exemple : POST /technologies
Données : form-data
Exemple : 
![image](https://github.com/edw70/Apirest/assets/133671255/9cdbef41-b7b7-4300-ab3c-752202b8457c)

#### Mettre à Jour une Technologie (id obligatoire)
URL : PUT /technologies/{id}
Données : form-data
Exemple : 
![image](https://github.com/edw70/Apirest/assets/133671255/8b8a7892-ae5d-4924-a431-e41d5fabdb4b)


### Remarques importantes :
Pour supprimer une catégorie ou une technologie, assurez-vous que toutes les ressources associées ont été supprimées au préalable.



        
        



            

