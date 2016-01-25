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

#### I.1.a. Controller

C'est une classe abstraite, mère de tous les controlleurs.
* loadModel($model) : Charge un model et fournit une variable portant son nom.

Exemple à l'intérieur du controlleur IndexControlleur :
```php
$this->loadModel('IndexModel');
$this->indexmodel->maFonction($params)
```

* render($view, $data = array()) : Appele la fonction render() sur la vue passée en paramètre en lui fournissant les variables passées dans $data

Exemple : 
```php
$data = array('dataUne' => $dataUne);
$this->render('dossier/fichier', $data);
```

* redirect($url) : Redirige à l'url passé en paramètre.
* redirectToRoute($route, $data) : Redirige à la route passé en paramètre suivit des différentes data fournies.
* createNotFoundException($description) : Lève une exception traçable.

#### I.1.b. Database

Fournit un accès simple à la base de donnée.

* execute($sql, $params = null) : Execute la requête (paramétrée si nécéssaire) passée en 1er paramètre avec les paramètres passés en 2nd paramètre.
* lastInsertId() : Rebvoie l'id généré par la dernière requête exécuté.
* [private] getBdd() : Fournit un accès la base de données.

#### I.1.c. Entity

C'est une classe abstraite, mère de toutes les entities. 

#### I.1.d. Kernel
 
La mission de ce singleton est d'appeler le controleur et l'action correspondant à l'url de la page demandée en la passant au routeur et de fournir un objet Request à l'action si celui ci est présent dans ses paramètres.

* response() : Génère la réponse à renvoyer au navigateur en fonction de l'url demandée.

```php
    public function response()
    {
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        $route = array_shift($params);
        return $this->generateResponse($route, $params);
    }
 ```   
    
* generateResponse($route = null, $params = array(), $internal = false) : Génère la réponse à renvoyer au navigateur en fonction de la route et des données passées en paramètre. Le paramètre $internal vaut vraie si la réponse est demandée par une vue (voir plus bas).

```php
    public function generateResponse($route = null, $params = array(), $internal = false) {
        $router = new Router();
        $request = Request::getInstance();
        $request->setInternal($internal);
        if($route)
            $route = $router->getRoute($route);
        else
            $route = $router->getDefaultRoute();
        $controller = $route->getController();
        $action = $route->getAction();
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $paramsOfFunction = $r->getParameters();
        $paramsToPass = array();
        $indexParams = 0;
        foreach ($paramsOfFunction as $param) {
            if($param->getClass() != NULL && $param->getClass()->getName() == 'Request')
                $paramsToPass[] = $request;
            else
                if(isset($params[$indexParams]))
                    $paramsToPass[] = $params[$indexParams++];
                else
                    $paramsToPass[] = null;
        }
        if(!empty($paramsToPass))
            return call_user_func_array(array($controller, $action), $paramsToPass);
        return $controller->{$action}();
    }
```

#### I.1.e. Mail

Cette classe contient les fonctions d'envoie de mail.

#### I.1.f. Model

C'est une classe abstraite, mère de tous les models. 

#### I.1.g. NotFoundException

C'est une exception levée lorsqu'un élément recherché n'a pas été trouvé (une vue par exemple).

#### I.1.e. Request

Fournit un moyen unique d'accéder aux variables $_POST, $_GET et à l'objet Session.
* isAjaxRequest() : Renvoie vraie si la requête HTTP est une requête faîte par Ajax.
* isInternal() : Renvoie vraie si la requête est interne, c'est à dire demandée par une vue.
* setInternal($internal) : Modifie la donnée membre privée $this->internal.
* get($name) : Renvoie la variable $_GET[$name] ou null si elle n'est pas définie.
* post($name) : Renvoie la variable $_POST[$name] ou null si elle n'est pas définie.
* getSession() : Retourne l'instance unique de l'objet Session.
* [static] getInstance() : Retourne l'instance unique de l'objet Request.

#### I.1.f. Route
 
Représente une route associant une url à un controlleur et à une action de celui ci.

* __construct($name, $controller, $action)
* getName()
* getController()
* getAction()

#### I.1.g. Router

Le routeur construit les différentes routes et fournit un moyen de récupérer la route voulue.

* getRoute($name) : Renvoie la route de nom $name.
* getDefaultRoute() : Renvoie la route par défaut.

```php
public function getDefaultRoute() {
    return new Route('index', 'index', 'index');
}
    
public function __construct() {
    // Route(routename, controllername, actionname);
    $this->table[] = $this->getDefaultRoute();
    $this->table[] = new Route('exampleroute', 'index', 'exemple');
}
```

#### I.1.h. Security

Fournit des fonctions concernant la protection globale du site. 

* [static] escape($string) : Echape la chaîne de caractères $string.
* [static generateKey() : Renvoie une chaine de caractère utilisable comme token ou clé.
* [static] encode($str) : Crypte la chaîne $str.
* [static] equals($hashedPassword, $userPassword) : Retourne vraie si un mot de passe utilisateur correspond à un mot de passe hashé.

#### I.1.i. Session

Commence une session et fournit un moyen unique d'y accéder.

```php
    const USER_IS_NOT_CONNECTED = 0;
    const USER_IS_CONNECTED = 1;
    const USER_IS_ADMIN = 2;
```
    
* get($name) : Renvoie la variable $_SESSION[$name] ou null si elle n’est pas définie.
* set($name, $value) : $_SESSION[$name] = $value.
* clear() : Réinitialise et détruit la session.
* isConnected() : Renvoie vraie si l'utilisateur courant est connecté.
* isGranted($role) : Renvoie vraie si l'utilisateur courant à un rôle supérieur ou égale à celui passé en paramètre.
* [static] getInstance() : Renvoie l'instance unique de l'objet Session.

#### I.1.j. TraceableException

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

#### I.1.k. View

* [static] getView($view, $data = array()) : Créer un object Vue avec $view comme paramètre et appèle la fonction render() sur cet objet.
* [static] getAsset($asset) : Retourne un lien absolu vers un fichier présent dans le dossier web/.
* [static] getUrlFromRoute($route) : Retourne un lien absolu vers une route.
* isGranted($role) : Raccourci vers la fonction isGranted de la Session.
* renderControllerAction($route, $data = array()) : Retourne la réponse d'une action d'un controleur correspondant à la route.
* escape($string) : Echape les entités HTML d'une chaîne.
* output($var, $default = '') : Affiche la variable $this->{$var} si défini ou $default sinon.
* render($view, $data = array()) : Inclue la vue $view en lui passant les variables contenues dans le tableau $data
 
```php
 public function render($view, $data = array()) {
        if (!empty($data)) {
            if (!empty($this->data)) {
                $this->data = array_merge($data, $this->data);
            }
            else {
                $this->data = $data;
            }
        }
        $viewspath = __DIR__ . DIRECTORY_SEPARATOR . '../views/';
        $path = $viewspath . $view . '.php';
        if (file_exists($path)) {
            $data['view'] = new ViewPart();
            extract($data);
            ob_start();
            require $path;
            $content_for_layout = ob_get_clean();
            if ($data['view']->super()) {
                $this->data['_content'] = $content_for_layout;
                $this->render($data['view']->super(), $this->data);
            }
            else {
                echo $content_for_layout;
            }
        }
        else {
            throw new NotFoundException("VIEW NOT FOUND | " . $path . " |");
        }
    }
```

#### I.1.l. ViewPart

Représente un morceau de vue qui peut hériter d'une autre vue ou en inclure d'autre.

* extend($extend) : Choisir le père de la vue.
* super() : Retourne le père de la vue.

### I.2 index.php

Tout les accès au nom de domaine http://cuscom.fr/aaron/\* ne contenant pas le caractère '.', signe d'un accès direct à une image ou à un fichier précis, sont redirigés sur le fichier index.php.
Son rôle est d'inclure les différentes classes du dossier app, les controlleurs et les models puis de demander au Kernel de générer la réponse au navigateur où d'afficher la traçe d'une exception si nécéssaire.

## I. Moteur de template

Le dossier /views/exemple présente une utilisation concrète du moteur de template crée pour le projet.

### I.1 layout.php

La fonction 'render' permet d'inclure une nouvelle vue avec les variables dont elle à besoin.
On peut aussi utiliser la fonction 'output' au lieu de la variable si on est pas sûr qu'elle existe :
$view->render('exemple/head', array('title' => $view->output('title')));
On peut vérifier si l'utilisateur à un certain rang avec la fonction 'isGranted' et avec les constantes de Session.
La variable '$_content' contient le code html de la vue remplissant cette template.


```php
<!DOCTYPE html>
<html>
    <?php
    $this->render('exemple/head', array('title' => $title));
    ?>
    <body>
        <?= $this->output('_content'); ?>
    </body>
</html>
```

### I.2 head.php

```php
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= View::getAsset('inc/...') ?>" />
</head>
```

### I.3 body.php

```php
<?php
$view->extend('exemple/layout');
?>
<body>
    <h1><?= $this->output('title', 'Titre par défaut') ?></h1>
    <h2><?= $this->output('title2', 'Sous-Titre par défaut') ?></h2>
    <?php if($this->isGranted(Session::USER_IS_CONNECTED)): ?>
        L'utilisateur est connecté !
    <?php endif; ?>
    <?= $_content ?>
    <hr>
    <?= $this->render('exemple/content'); ?>
    <hr>
</body>
```

### I.1 content.php

La fonction 'extend' permet de signaler que cette vue ira remplir un morceau manquant de la template qui lui est passé en paramètre. 
La variable $content est envoyé par un controlleur à la vue. Il ne faut pas la confondre avec $_content, $this et $view qui sont respectivement le contenu de la vue fille dans une vue mère, une référence vers la vue et une référence vers le morceau de vue dans lequel on se trouve.

```php
<?php
$view->extend("exemple/body");
?>
<h3>Contenu</h3>
<pre>
    <?= $content ?>
</pre>
<h3>Contenu pouvant ne pas avoir été précisé avec une valeur par défaut</h3>
<pre>
    <?= $this->output('content', 'Mon contenu par défaut') ?>
</pre>
<h3>Contenu protégé</h3>
<pre>
    <?= $this->escape($content) ?>
</pre>
```
