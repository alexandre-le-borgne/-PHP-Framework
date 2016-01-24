<?php

class AdminController extends Controller
{
    const DELETED_OK = 'deleted';
    const ERROR_NO_USER = 'nouser';

    private function isAdmin($request)
    {
        if (!($request->getSession()->isGranted(Session::USER_IS_ADMIN)))
            $this->redirectToRoute('index');
    }

    function IndexAction(Request $request)
    {
        $this->isAdmin($request);
        $data = array();
        $this->render('layouts/admin/adminDashboard', $data);
    }

    function UsersAction(Request $request, $message)
    {
        $this->isAdmin($request);
        $this->loadModel('AdminModel');
        $data = $this->adminmodel->getAllUsers();
        $allData = array('users' => $data);
        if ($message == self::DELETED_OK)
            $allData['deleted'] = 'Utilisateur supprimé';
        if ($message == self::ERROR_NO_USER)
            $allData['error'] = 'Utilisateur inexistant';
        $this->render('layouts/admin/manageUsers', $allData);
    }

    function deleteUserAction(Request $request)
    {
        $this->isAdmin($request);
        $id = $request->post('id');
        $this->loadModel('UserModel');
        /** @var UserEntity $user */
        $user = $this->usermodel->getById($id);

        if ($request->post('seeBlog'))
        {
            $this->redirectToRoute('channel/' . $user->getUsername());
            return;
        }

        $this->loadModel('AdminModel');
        if ($user != null)
        {
            $this->adminmodel->deleteUser($id);
            $this->redirectToRoute('adminusers/' . self::DELETED_OK);
        }
        else
            $this->redirectToRoute('adminusers/' . self::ERROR_NO_USER);
    }
}