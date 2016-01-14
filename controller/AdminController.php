<?php

/**
 *
 */
class AdminController extends Controller
{
    function IndexAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_ADMIN))
        {
            $data = array
            ();



            $this->render('layouts/adminDashboard', $data);
        } else
        {
            throw new Exception('Utilisateur non admin');
        }
    }

}