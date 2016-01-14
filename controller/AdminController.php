<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 14/01/16
 * Time: 10:45
 */

class AdminController
{
    function AdminAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_ADMIN))
        var_dump($request);
    }

}