# PHP-Framework

* Julien Jandot
* Theo Jeanjean
* Maxime Junique
* Alexandre Le Borgne

## I. Architecture MVC

* app
    * core
        * exceptions
            * NotFoundException.php
            * TraceableException.php
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
        * Security.php
        * Session.php
        * View.php
        * ViewManager.php
        * ViewPart.php
    * App.php
* controller
* entity
* model
* services
    * databases
        * EmptyDatabase.php
        * SqlDatabase.php
    * GenericSerializer.php
    * TimeConverter.php
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