<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 24/01/2016
 * Time: 01:13
 */
class AjaxController extends Controller
{
    // Action frontale des reqûetes Ajax.
    public function IndexAction(Request $request) {
        if(!$request->getSession()->isGranted(Session::USER_IS_CONNECTED))
            return;
        echo $request->post('action');
        switch($request->post('action')) {
            case 'like':
                $this->LikeAction($request);
                break;
            case 'nolike':
                $this->NoLikeAction($request);
                break;
            case 'blog':
                $this->BlogAction($request);
                break;
            case 'noblog':
                $this->NoBlogAction($request);
                break;
            case 'search':
                $this->SearchAction($request);
                break;
            default:
        }
    }

    private function SearchAction(Request $request) {
        $channel = $request->post('channel');
        $this->loadModel('UserModel');
        echo json_encode($this->usermodel->getLike($channel));
    }

    private function LikeAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $articleEntity = $this->articlemodel->getById($post);
        if($articleEntity) {
            /** @var ArticlesFavorisEntity $articlesFavorisEntity */
            $articlesFavorisEntity = $this->articlemodel->getArticleFromFavoris($request->getSession()->get('id'), $articleEntity->getId());
            if(!$articlesFavorisEntity) {
                $articlesFavorisEntity = new ArticlesFavorisEntity();
                $articlesFavorisEntity->setAccount($request->getSession()->get('id'));
                $articlesFavorisEntity->setArticle($articleEntity->getId());
                $articlesFavorisEntity->persist();
            }
        }
    }

    private function NoLikeAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $this->articlemodel->removeArticleFromFavoris($request->getSession()->get('id'), $post);
    }

    private function BlogAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $articleEntity = $this->articlemodel->getById($post);
        echo "bite";
        if($articleEntity) {
            $articleEntity = $this->articlemodel->getArticleFromBlog($request->getSession()->get('id'), $articleEntity->getId());
            if(!$articleEntity) {
                $blogEntity = new BlogEntity();
                $blogEntity->setAccount($request->getSession()->get('id'));
                $blogEntity->setArticle($post);
                $blogEntity->persist();
            }
        }
    }

    private function NoBlogAction(Request $request) {
        $post = ltrim(strstr($request->post('id'), '_'), '_');
        $this->loadModel('CategoryModel');
        $this->loadModel('ArticleModel');
        /** @var ArticleEntity $articleEntity */
        $this->articlemodel->removeArticleFromBlog($request->getSession()->get('id'), $post);
    }
}