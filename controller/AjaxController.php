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
                $this->LikeAction($request);
                break;
            case 'nolike':
                $this->NoLikeAction($request);
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
            $data = array($request->getSession()->get('id'), $articleEntity->getId());
            $data = $db->execute("SELECT * FROM articlesfavoris WHERE account = ? AND article = ?", $data);
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'EmailEntity');
            $emailEntity = $data->fetch();
            if(!$emailEntity)
            {
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
        $this->articlemodel->removeFromFavoris($request->getSession()->get('id'), $post);
    }
}