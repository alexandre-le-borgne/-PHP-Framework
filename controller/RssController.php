<?php
/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 20/01/16
 * Time: 09:05
 */

class RssController extends  Controller{

    public function IndexAction($id = null){
        if(intval($id)){

        }
        else{
            $this->loadModel('RssModel');
            $rss = $this->rssmodel->getList();
            $data = array('title' => 'Liste des flux rss', 'articles' => $rss);
            $this->render('layouts/articles', $data);

        }
    }

    public function AddRSSStreamAction(Request $request){
        $categoryTitle = $request->post('category');
        $firstUpdate = $request->post('firstUpdate');
        $url = $request->post('url_flux');

        $this->loadModel('CategoryModel');
        $this->loadModel('RssModel');
    }

}