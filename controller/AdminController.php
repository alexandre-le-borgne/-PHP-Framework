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

        if ($userEntity == null)
            throw new Exception('T une merde, ton compte est pas enregistre. pd <3 ! ');
        //On reverifie que l'utilisateur est admin
        if ($userEntity->getAccountLevel() == UserModel::ACCOUNT_LEVEL_ADMIN)
        {
            echo "c bon t admin mon pote\n\rVoila ton entité associée : \n\r";
            var_dump($userEntity);
        }

    }

}