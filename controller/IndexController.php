<?php

class IndexController extends Controller
{
    public function IndexAction(Request $request)
    {
        $this->render('index/home');
    }

}