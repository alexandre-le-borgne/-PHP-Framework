<?php

class Router
{

    private $table = array();

    public function __construct()
    {

        //"exampleroute" is the name of the route, e.g. /exampleroute
        //Here, class names are used rather than instances so instances are only ever created when needed, otherwise every model, view and
        //controller in the system would be instantiated on every request, which obviously isn't good!
        // Route(routename, controllername, actionname);
        $this->table[] = $this->getDefaultRoute();


        //Exemple les layouts
        $this->table[] = new Route('exemple',           'exemple',      'index');

        $this->table[] = new Route('cron',              'index',        'cron');

        $this->table[] = new Route('ajax',              'ajax',        'index');

        $this->table[] = new Route('login',             'user',         'login');
        $this->table[] = new Route('logout',            'user',         'logout');
        $this->table[] = new Route('preregister',       'user',         'preRegister');
        $this->table[] = new Route('register',          'user',         'register');
        $this->table[] = new Route('mailvalidation',    'user',         'mailValidation');
        $this->table[] = new Route('facebook',          'user',         'facebook');
        $this->table[] = new Route('google',            'user',         'google');
        $this->table[] = new Route('forgotform',        'user',         'forgotform');
        $this->table[] = new Route('pwdforgot',         'user',         'pwdforgot');
        $this->table[] = new Route('resetform',         'user',         'resetForm');
        $this->table[] = new Route('resetpassword',     'user',         'resetpassword');
        $this->table[] = new Route('channel',           'user',        'channel');

        $this->table[] = new Route('article',           'article',      'article');
        $this->table[] = new Route('category',          'article',      'category');

        $this->table[] = new Route('admin',             'admin',        'index');
        $this->table[] = new Route('adminusers',        'admin',        'users');
        $this->table[] = new Route('deleteuser',        'admin',        'deleteUser');

        /** Ajouts de flux */
        $this->table[] = new Route('addtwitterstream',  'twitter',      'addTwitterStream');
        $this->table[] = new Route('addrssstream',      'rss',          'addRSSStream');
        $this->table[] = new Route('addimapstream',     'email',        'addIMAPStream');
        $this->table[] = new Route('favoris',           'article',      'favoris');

        //Test
        $this->table[] = new Route('twitter',           'twitter',      'testTwitter');
        $this->table[] = new Route('rss',               'index',        'rss');
        $this->table[] = new Route('addStream',         'index',        'AddStream');
        $this->table[] = new Route('email',             'email',        'index');
    }

    public function getRoute($name)
    {
        $name = strtolower($name);
        foreach ($this->table as $route)
            if ($route->getName() == $name)
                return $route;
        return $this->getDefaultRoute();
    }

    public function getDefaultRoute()
    {
        return new Route('index', 'index', 'index');
    }
}