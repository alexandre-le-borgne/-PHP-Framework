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
        $this->table[] = new Route('aside',             'index',        'aside');
        $this->table[] = new Route('search',            'index',        'search');

        $this->table[] = new Route('ajax',              'ajax',         'index');

        $this->table[] = new Route('login',             'user',         'login');
        $this->table[] = new Route('logout',            'user',         'logout');
        $this->table[] = new Route('register',          'user',         'register');
        $this->table[] = new Route('mailvalidation',    'user',         'mailValidation');
        $this->table[] = new Route('facebook',          'user',         'facebook');
        $this->table[] = new Route('google',            'user',         'google');
        $this->table[] = new Route('forgotform',        'user',         'forgotform');
        $this->table[] = new Route('pwdforgot',         'user',         'pwdforgot');
        $this->table[] = new Route('resetform',         'user',         'resetform');
        $this->table[] = new Route('channel',           'user',         'channel');
        $this->table[] = new Route('profile',           'user',         'profile');
        $this->table[] = new Route('resetpassword',     'user',         'resetpassword');
        $this->table[] = new Route('deletecategory',    'user',         'deleteCategory');
        $this->table[] = new Route('deletestream',      'user',         'deleteStream');


        $this->table[] = new Route('article',           'article',      'article');
        $this->table[] = new Route('category',          'article',      'category');
        $this->table[] = new Route('stream',            'article',      'stream');

        $this->table[] = new Route('admin',             'admin',        'index');
        $this->table[] = new Route('adminusers',        'admin',        'users');
        $this->table[] = new Route('deleteuser',        'admin',        'deleteUser');

        $this->table[] = new Route('addstream',         'article',      'addStream');
        $this->table[] = new Route('favoris',           'article',      'favoris');
        $this->table[] = new Route('blog',              'article',      'blog');

        $this->table[] = new Route('followchannel',     'follower',     'followChannel');
        $this->table[] = new Route('followernumbers',   'follower',     'followerNumbers');
        $this->table[] = new Route('unfollow',          'follower',     'unFollow');


        /** Ajouts de flux */
        $this->table[] = new Route('addtwitterstream',  'twitter',      'addTwitterStream');
        $this->table[] = new Route('addrssstream',      'rss',          'addRSSStream');
        $this->table[] = new Route('addemailstream',    'email',        'addEmailStream');

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