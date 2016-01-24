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
        $this->followmodel->follow($channel, $id);
        $this->redirectToRoute('index');
    }
}