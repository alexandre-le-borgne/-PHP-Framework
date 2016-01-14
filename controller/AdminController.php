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
        $this->loadModel('UserModel');
        $userEntity = $this->usermodel->getById($request->getSession()->get('id'));


        var_dump($userEntity);


    }

}