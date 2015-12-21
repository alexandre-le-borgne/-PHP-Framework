# ProjectPHP

* Julien Jandot
* Theo Jeanjean
* Maxime Junique
* Alexandre Le Borgne

## I. Architecture MVC

* app
* controller
* model
* vendor
* views
* web
* index.php

### I.1 app

* #### I.1.a. Controller

C'est une classe abstraite, mère de tout les controlleurs.
* set($data) : NOT IMPLEMENTED
* loadModel($model) : Charge un model est fournit une variable portant son nom.

Exemple à l'intérieur du controlleur IndexControlleur :
```php
$this->loadModel('IndexModel');
$this->indexmodel->maFonction($params)
```

* render($view, $data = array()) : Appele la fonction render() sur la vue passé en paramètre en lui fournissant les variables passer dans $data

Exemple : 
```php
$data = array('errors' => $errors);
$this->render('persists/home', $data);
```

* redirect($url) : Redirige simplement à l'url passé en paramètre
* redirectToRoute($route, $data) : NOT IMPLEMENTED
* createNotFoundException($description) : Lève une exception traçable.


* #### I.1.b. Database

Fournit un accès simple à la base de donnée.

* execute($sql, $params = null) : Execute la requete (paramétré si nécéssaire) passé en 1er paramètre avec les paramètres passé en 2nd paramètre.
* [private] getBdd() : Fournit un accès la base de données.

* #### I.1.c. Kernel
 
La mission de ce singleton est d'appelé le controleur et l'action correspondant à l'url de la page demandé en la passant au routeur et de fournir un objet Request à l'action si celui ci est présent dans ses paramètres.

* response() : Génère la réponse à renvoyé au navigateur en fonction de l'url demandée.

```php
    public function response()
    {
        $router = new Router();
        $request = new Request();
        $params = explode('/', $request->get('url'));
        if (isset($params[0]) && $params[0] != '')
            $route = $router->getRoute($params[0]);
        else
            $route = $router->getDefaultRoute();
        $controller = $route->getController();
        $action = $route->getAction();
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $params = $r->getParameters();
        foreach ($params as $param) {
            if($param->getClass()->getName() == 'Request')
                return $controller->{$action}($request);
        }
        return $controller->{$action}();
    }
```

* #### I.1.d. Model

C'est une classe abstraite, mère de tout les models. 

* #### I.1.e. Request

Fournit un moyen unique d'accéder aux variables $_POST, $_GET et à l'objet Session.
* isAjaxRequest() : Renvoie vraie si la requête HTTP est une requête faite par Ajax.
* get($name) : Renvoie la variable $_GET[$name] ou null si elle n'est pas définie.
* post($name) : Renvoie la variable $_POST[$name] ou null si elle n'est pas définie.
* getSession() : Retourne l'instance unique de l'objet Session.

* #### I.1.f. Route
 
Représente une route associant une url à un controlleur et à une action de celui ci.

* __construct($name, $controller, $action)
* getName()
* getController()
* getAction()

* #### I.1.g. Router

Le routeur construit les différentes routes et fournit y moyen de récupérer la route voulue.

* getRoute($name) : Renvoie la route de nom $name.
* getDefaultRoute() : Renvoie la route par défaut.
```php
public function __construct() {
    // Route(routename, controllername, actionname);
    $this->table[] = $this->getDefaultRoute();
    $this->table[] = new Route('exampleroute', 'index', 'exemple');
}
```

* #### I.1.h. Security

Fournit des fonctions concernant la protection globale du site. 

* display($string) : Echape la chaîne de caractères $string.
* encode($str) : Crypte la chaîne $str.

* #### I.1.i. Session

Commence une session et fournit un moyen unique d'y accéder.

* get($name) : Renvoie la variable $_SESSION[$name] ou null si elle n’est pas définie.
* set($name, $value) : $_SESSION[$name] = $value.
* clear() : Réinitialise et détruit la session.

* #### I.1.j. TraceableException

Implémente la classe Exception de base et lui ajoute quelques fonctions.

* generateCallTrace() : Renvoie un tableau contenant la liste des appels de fonctions jusqu'à la levée de l'exception.
* show() : Appel la fonction render() sur la vue, layout des exception en lui fournissant les données de l'exception levée.
* getData() : Retourne un tableau contenant les informations relatives à l'exception levée.

```php
public function getData()
{
    return array(
        'code' => $this->getCode(),
        'name' => get_class($this),
        'message' => $this->getMessage(),
        'file' => $this->getFile(),
        'line' => $this->getLine(),
        'trace' => $this->generateCallTrace()
    );
}
```

* #### I.1.k. View

* __construct($view) : Construit un objet Vue avec $view comme chemin relatif à la vue.
* [static] getView($view, $data = array()) : Créer un object Vue avec $view comme paramètre et appel la fonction render() sur cet objet.
* render($data = array()) : Inclue la vue en lui passant les variables contenu dans le tableau $data
 
```php
public function render($data = array()) {
        $viewspath = __DIR__.DIRECTORY_SEPARATOR.'../views/';
        $path = $viewspath.$this->view.'.php';
        if(file_exists($path)) {
            if(!empty($data))
                extract($data);
            ob_start();
            require $path;
            $content_for_layout = ob_get_clean();
            if($this->layout == false)
                echo $content_for_layout;
            else
                require ($viewspath.$this->layout.'.php');
        }
        else {
            throw new TraceableException("VIEW NOT FOUND | ".$path." |");
        }
    }
```

### I.2 controller

### I.3 model

### I.4 vendor

### I.5 views

### I.6 web

### I.7 index.php

Tout les accès au nom de domaine http://cuscom.fr/aaron/\* ne contenant pas le caractère '.', signe d'un accès direct à une image ou à un fichier précis, sont redirigés sur le fichier index.php.
Son rôle est d'inclure les différentes classes du dossier app, les controlleurs et les models puis de demander au Kernel de généré la réponse au navigateur où d'afficher la trace d'une exception si nécéssaire.