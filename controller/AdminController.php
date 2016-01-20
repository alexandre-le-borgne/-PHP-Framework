<?php

/**
 *
 */
class AdminController extends Controller
{
    private function isAdmin($request)
    {
        if (!($request->getSession()->isGranted(Session::USER_IS_ADMIN)))
            throw new Exception('utilisateur non admin');
        //$this->redirectToRoute('index');
    }

    function IndexAction(Request $request)
    {
        $this->isAdmin($request);
        $data = array();
        $this->render('layouts/admin/adminDashboard', $data);
    }

    function UsersAction(Request $request)
    {
        $this->isAdmin($request);
        $this->loadModel('AdminModel');
        $data = $this->adminmodel->getAllUsers();
        $this->render('layouts/admin/manageUsers', array('users' => $data));
    }
}