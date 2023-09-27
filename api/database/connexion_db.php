<?php
class database {
    // Propriétés de la base de données
    private $host = "mysql";  // Nom de l'hôte MySQL
    private $db_name;  // Le nom de la base de données sera récupéré des variables d'environnement
    private $username;  // Le nom d'utilisateur sera récupéré des variables d'environnement
    private $password;  // Le mot de passe de la base de données sera récupéré des variables d'environnement
    public $connexion;

    // Méthode pour obtenir la connexion
    public function getConnection() {
        // On commence par fermer la connexion si elle existait
        $this->connexion = null;

        // Récupérer les variables d'environnement
        $this->db_name = getenv('MYSQL_DATABASE');
        $this->username = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_PASSWORD');

        // On essaie de se connecter
        try {
            $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->connexion->exec("set names utf8"); // On force les transactions en UTF-8
        } catch (PDOException $exception) {  // On gère les erreurs éventuelles
          //  echo "Erreur de connexion : " . $exception->getMessage();
            die("Erreur de connexion à la base de données : " . $exception->getMessage());
        }
        // On retourne la connexion
        return $this->connexion;
    }
}
