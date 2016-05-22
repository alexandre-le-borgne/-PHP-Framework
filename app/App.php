<?php

/**
 * Class App
 */
class App {
    /**
     * Return the database you want to use
     * @return IDatabase
     */
    public function getDatabase() {
        return new SqlDatabase('localhost', 'framework', 'root');
    }

    /**
     * Return the list of your routes of your app
     * @return array
     */
    public function getRoutes() {
        return array(
            new Route('exemple',            'exemple',          'index'),
            new Route('test',               'test',             'index'),
            new Route('gettest',            'test',             'get'),
        );
    }
}