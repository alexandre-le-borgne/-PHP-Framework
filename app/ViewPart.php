<?php
/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 18/01/16
 * Time: 15:59
 */

class ViewPart {
    private $extend = null;
    public function extend($extend) {
        $this->extend = $extend;
    }
    public function super() {
        return $this->extend;
    }
}