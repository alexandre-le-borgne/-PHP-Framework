<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 14/01/16
 * Time: 10:45
 */
class AdminController extends Controller
{
    function IndexAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_ADMIN))
        {
            //


//            $this->render('adm')
        } else
        {
            throw new Exception('Utilisateur non admin');
        }
    }

}