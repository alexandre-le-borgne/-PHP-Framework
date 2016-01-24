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

    public function addRSSStreamAction(Request $request){
        $categoryTitle = $request->post('category');
        $firstUpdate = $request->post('firstUpdate');
        $url = $request->post('url_flux');

        var_dump($categoryTitle);
        var_dump($firstUpdate);
        var_dump($url);

        $this->loadModel('CategoryModel');
        $this->loadModel('RssModel');
        $url = $this->rssmodel->resolveFile($url);
        $userId = $request->getSession()->get('id');
        $rssEntity = $this->rssmodel->createStream($url, $firstUpdate);

        $categoryEntity = $this->categorymodel->createCategory($userId, $categoryTitle);

        $streamCategoryEntity = new StreamCategoryEntity();
        $streamCategoryEntity->setCategory($categoryEntity->getId());
        $streamCategoryEntity->setStream($rssEntity->getId());
        $streamCategoryEntity->setStreamType(ArticleModel::RSS);
        $streamCategoryEntity->persist();

        $this->twittermodel->streamCron($rssEntity);
        //$this->redirectToRoute('index');
    }

}