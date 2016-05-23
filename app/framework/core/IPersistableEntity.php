<?php

/**
 * Interface IPersistableEntity
 */
interface IPersistableEntity
{
    /**
     * @return PersistableModel
     */
    static function getModel();
}