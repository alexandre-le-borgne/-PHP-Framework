<?php

class ViewPart
{
    private $extend = null;

    public function extend($extend)
    {
        $this->extend = $extend;
    }

    public function super()
    {
        return $this->extend;
    }
}