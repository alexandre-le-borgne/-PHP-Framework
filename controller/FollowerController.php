<?php

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
        return 42;
    }

    public function UnFollowAction(Request $request)
    {
        $this->loadModel('FollowerModel');
        $followed = $request->post('id');
        $user = $request->getSession()->get('id');
        $this->followermodel->unfollow($followed, $user);
        $this->redirectToRoute('profile');
    }
}