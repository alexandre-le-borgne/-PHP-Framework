<?php

/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 19:20
 */
class FollowerController extends Controller
{
    public function FollowChannelAction(Request $request, $channel)
    {
        if (!$channel)
        {
            $this->redirectToRoute('index');
            return;
        }
        $this->loadModel('FollowerModel');

        $id = $request->getSession()->get('id');
        $this->followermodel->follow($channel, $id);
        $this->redirectToRoute('index');
    }

    public function FollowerNumbersAction()
    {
        //Todo finir
        return 12;
    }

    public function UnFollowAction()
    {

    }
}