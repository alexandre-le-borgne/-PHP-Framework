<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 24/01/2016
 * Time: 01:13
 */
class AjaxController extends Controller
{
    public function IndexAction(Request $request) {
        if(!$request->getSession()->isGranted(Session::USER_IS_CONNECTED))
            return;
        switch($request->post('action')) {
            case 'like':
                $this->LikeAction();
                break;
            case 'nolike':
                $this->NoLikeAction();
                break;
            default:
        }
    }

    private function LikeAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $articleEntity = $this->articlemodel->getById($post);
        if($articleEntity) {
            $articlesFavorisEntity = new ArticlesFavorisEntity();
            $articlesFavorisEntity->setAccount($request->getSession()->get('id'));
            $articlesFavorisEntity->setArticle($articleEntity->getId());
            $articlesFavorisEntity->persist();
        }
    }

    private function NoLikeAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $this->articlemodel->removeFromFavoris($request->getSession()->get('id'), $post);
    }
}