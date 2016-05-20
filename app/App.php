<?php
/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 11:28
 */

class App {
    public function getDatabase() {
        return new SqlDatabase('localhost', 'framework', 'root');
    }
    
    public function getRoutes() {
        return array(
            new Route('exemple',            'exemple',          'index'),
            new Route('test',               'test',             'index'),
            new Route('gettest',            'test',             'get'),
        );
    }
}