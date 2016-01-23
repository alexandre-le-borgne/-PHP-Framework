<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:36
 *
 */

class Database
{
    private $bdd;

    const DATE_FORMAT = 'Y-m-d H:i:s';

    // Exécute une requête SQL éventuellement paramétrée
    public function execute($sql, $params = null)
    {
        echo $sql."<br><hr><br>";
        var_dump($params);

        if ($params == null) {
            $resultat = $this->getBdd()->query($sql);    // exécution directe
        } else {
            if(!is_array($params)) throw new Exception("Paramètres innatendus : " . var_dump($params));
            try {
                $resultat = $this->getBdd()->prepare($sql);  // requête préparée

                $resultat->execute($params);
            } catch (PDOException $e) {
                 var_dump($resultat->debugDumpParams());
                throw new Exception($e->getMessage());
            }
        }
        return $resultat;

    }

    public function lastInsertId()
    {
        return $this->bdd->lastInsertId();
    }

    // Renvoie un objet de connexion à la BD en initialisant la connexion au besoin
    private function getBdd()
    {
        if ($this->bdd == null) {

            // Création de la connexion
            $this->bdd = new PDO('mysql:host=mysql-alex83690.alwaysdata.net;dbname=alex83690_aaron;charset=utf8',
                'alex83690', 'password', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->bdd->query("SET NAMES 'utf8'");
        }
        return $this->bdd;
    }
}