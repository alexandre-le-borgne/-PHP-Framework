<?php

/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 21/01/16
 * Time: 16:49
 */

interface StreamModel
{
    function cron();

    function getStreamById($id);
}