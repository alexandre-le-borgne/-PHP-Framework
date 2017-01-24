<?php
/**
 * Ce code va tester votre serveur pour déterminer quel serait le meilleur "cost".
 * Vous souhaitez définir le "cost" le plus élevé possible sans trop ralentir votre serveur.
 * 8-10 est une bonne base, mais une valeur plus élevée est aussi un bon choix à partir
 * du moment où votre serveur est suffisament rapide ! Le code suivant espère un temps
 * ≤ à 50 millisecondes, ce qui est une bonne base pour les systèmes gérants les identifications
 * intéractivement.
 */
$timeTarget = 0.05; // 50 millisecondes

$cost = 8;
do {
    $cost++;
    $start = microtime(true);
    password_hash("test", PASSWORD_DEFAULT, ["cost" => $cost]);
    $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Valeur de 'cost' la plus appropriée : " . $cost . "\n";

# PHP-Framework

* app
    * framework
        * core
            * Controller.php
            * Entity.php
            * EntityManager.php
            * IDatabase.php
            * IPersistableEntity.php
            * Kernel.php
            * Model.php
            * PersistableEntity.php
            * PersistableModel.php
            * Request.php
            * Response.php
            * Route.php
            * Router.php
            * Session.php
            * View.php
            * ViewManager.php
            * ViewPart.php
        * databases
            * EmptyDatabase.php
            * SqlDatabase.php
        * exceptions
            * NotFoundException.php
            * TraceableException.php
        * services
            * GenericSerializer.php
            * Security.php
            * TimeConverter.php
    * App.php
* controller
* entity
* model
* services
* vendor
* views
    * core
        * exception.php
* web
    * css
    * img
    * inc
    * js
* index.php
