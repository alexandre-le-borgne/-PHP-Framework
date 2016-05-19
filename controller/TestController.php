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
//        /**
//         * @var UserModel $userModel
//         */
//        $userModel = $this->loadModel('user');
        $em = $this->getEntityManager();
        $user = new UserEntity();
        $user->setUsername('test');
        $user->setPassword('pwd');

        $em->persist($user);
        $em->flush();
        $this->render('app/test', array('user' => $user));
    }
}