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

    // Exécute une requête SQL éventuellement paramétrée
    public function execute($sql, $params = null)
    {
        if ($params == null) {
            $resultat = $this->getBdd()->query($sql);    // exécution directe
        } else {
            if(!is_array($params)) throw new Exception("Paramètres innatendus : " . var_dump($params));
            try {
                $resultat = $this->getBdd()->prepare($sql);  // requête préparée
                $resultat->execute($params);
            } catch (PDOException $e) {
                // var_dump($resultat->debugDumpParams());
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
            $this->bdd = new PDO('mysql:host=cuscomfrmkadmin.mysql.db;dbname=cuscomfrmkadmin;charset=utf8',
                'cuscomfrmkadmin', 'MAXsky1995', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return $this->bdd;
    }
}