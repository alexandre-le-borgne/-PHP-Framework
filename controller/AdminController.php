<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 14/01/16
 * Time: 10:45
 */
class AdminController extends Controller
{
    function AdminAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_ADMIN))
        {
            echo "c bon t admin mon pote ! Voila ton entitée associée : ";
        }
        else
        {
            echo "degage de la t pas admin pd";
        }
    }

}