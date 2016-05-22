<?php

/**
 * Class Entity
 */
abstract class Entity
{
    /**
     * @var int|null
     */
    protected $id = null;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
