<?php

/**
 * Class PersistableEntity
 */
abstract class PersistableEntity extends Entity
{
    /**
     * @var string|null
     */
    protected $created_at = null;

    /**
     * @var string|null
     */
    protected $modified_at = null;

    /**
     * @return PersistableModel
     */
    public static function getModel()
    {
        $string = new StringManipulation();
        $calledClass = get_called_class();
        $name = $string->str_lreplace('Entity', '', $calledClass);
        $model = $name . 'Model';
        return new $model($calledClass);
    }

    /**
     * @return array
     */
    public function getPersistedFields()
    {
        $fields = get_object_vars($this);
        unset($fields['created_at']);
        unset($fields['modified_at']);
        foreach($fields as $k => $field)
            if(is_object($field) || is_array($field) || is_callable($field))
                unset($fields[$k]);
        return $fields;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param string $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
    }
}