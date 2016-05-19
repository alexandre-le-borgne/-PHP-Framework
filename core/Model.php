<?php

abstract class Model
{
    /**
     * @var $database Database
     */
    protected $database;

    /**
     * @var $entityManager EntityManager
     */
    protected  $entityManager;

    public function __construct(EntityManager $entityManager, Database $database)
    {
        $this->entityManager = $entityManager;
        $this->database = $database;
    }
}