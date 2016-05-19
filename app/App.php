<?php
/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 11:28
 */

class App {
    public function getDatabase() {
        return new SqlDatabase('mysql-alex83690.alwaysdata.net', 'alex83690_aaron', 'alex83690', 'password');
    }
    
    public function getRoutes() {
        return array(
            new Route('exemple',           'exemple',      'index'),
            new Route('cron',              'index',        'cron'),
            new Route('aside',             'index',        'aside'),
            new Route('search',            'index',        'search'),
    
            new Route('ajax',              'ajax',         'index'),
    
            new Route('login',             'user',         'login'),
            new Route('logout',            'user',         'logout'),
            new Route('register',          'user',         'register'),
            new Route('mailvalidation',    'user',         'mailValidation'),
            new Route('facebook',          'user',         'facebook'),
            new Route('google',            'user',         'google'),
            new Route('forgotform',        'user',         'forgotform'),
            new Route('pwdforgot',         'user',         'pwdforgot'),
            new Route('resetform',         'user',         'resetform'),
            new Route('channel',           'user',         'channel'),
            new Route('profile',           'user',         'profile'),
            new Route('resetpassword',     'user',         'resetpassword'),
            new Route('deletecategory',    'user',         'deleteCategory'),
            new Route('deletestream',      'user',         'deleteStream'),
    
    
            new Route('article',           'article',      'article'),
            new Route('category',          'article',      'category'),
            new Route('stream',            'article',      'stream'),
    
            new Route('email',             'email',        'index'),
    
            new Route('admin',             'admin',        'index'),
            new Route('adminusers',        'admin',        'users'),
            new Route('deleteuser',        'admin',        'deleteUser'),
    
            new Route('addstream',         'article',      'addStream'),
            new Route('favoris',           'article',      'favoris'),
            new Route('blog',              'article',      'blog'),
    
            new Route('followchannel',     'follower',     'followChannel'),
            new Route('followernumbers',   'follower',     'followerNumbers'),
            new Route('unfollow',          'follower',     'unFollow'),
    
    
            /** Ajouts de flux */
            new Route('addtwitterstream',  'twitter',      'addTwitterStream'),
            new Route('addrssstream',      'rss',          'addRSSStream'),
            new Route('addemailstream',    'email',        'addEmailStream')
        );
    }
}