<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 22:46
 */
class TestController extends Controller
{
    public function IndexAction() {
        $em = $this->getEntityManager();
        $user = new UserEntity();
        $user->setUsername('test');
        $user->setPassword('pwd');

        $em->persist($user);
        $em->flush();
        $this->render('app/test', array('user' => $user));
    }

    public function GetAction(Request $request, $id) {
        /**
         * @var UserModel $userModel
         */
        $userModel = $this->loadModel('user');
        $this->render('app/test', array('user' => $userModel->find($id)));
    }
}