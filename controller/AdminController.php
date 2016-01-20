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
        $this->render('layouts/adminDashboard', $data);
    }

    function UsersAction(Request $request)
    {
        $this->isAdmin($request);
        $this->loadModel('AdminModel');
        $data = $this->adminModel->getAllUsers();
        throw new TraceableException('coucou');
        $this->render('layouts/manageUsers', array('users' => $data));
    }
}